<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketMessage;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SupportTicketController extends Controller
{
    use Upload, Notify;

    public function __construct()
    {
        $this->theme = template();
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function index()
    {
        if (!auth()->check()) {
            abort(404);
        }

        $tickets = SupportTicket::where('user_id', auth()->id())
            ->latest()
            ->paginate(basicControl()->paginate);

        return view(template() . 'user.support_ticket.index', compact('tickets'));
    }

    public function create()
    {
        return view(template() . 'user.support_ticket.create');
    }

    public function store(Request $request)
    {
        $random = rand(100000, 999999);
        $this->newTicketValidation($request);
        $ticket = $this->saveTicket($request, $random);
        $message = $this->saveMsgTicket($request, $ticket);

        if (!empty($request->attachments)) {
            $numberOfAttachments = count($request->attachments);
            for ($i = 0; $i < $numberOfAttachments; $i++) {
                if ($request->hasFile('attachments.' . $i)) {
                    $file = $request->file('attachments.' . $i);
                    $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null, null, 'webp', null, null, null);
                    if (empty($supportFile['path'])) {
                        throw new \Exception('File could not be uploaded.');
                    }
                    $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                }
            }
        }

        $msg = [
            'user' => optional($ticket->user)->username,
            'ticket_id' => $ticket->ticket
        ];
        $action = [
            "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
            "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
            "link" => route('admin.ticket.view', $ticket->id),
            "icon" => "fas fa-ticket-alt text-white"
        ];
        $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);
        $this->adminMail('SUPPORT_TICKET_CREATE', $msg);
        return redirect()->route('user.ticket.list')->with('success', 'Your ticket has been pending.');
    }

    public function ticketView($ticketId)
    {
        $page_title = 'View Ticket';
        $ticket = SupportTicket::where('ticket', $ticketId)->latest()->with('messages')->firstOrFail();
        $user = Auth::user();
        return view(template() . 'user.support_ticket.view', compact('ticket', 'user', 'page_title'));
    }

    public function reply(Request $request, $id)
    {

        if ($request->replayTicket == 1) {
            $images = $request->file('attachments');
            $allowedExtensions = array('jpg', 'png', 'jpeg', 'pdf');
            $this->validate($request, [
                'attachments' => [
                    'max:4096',
                    function ($fail) use ($images, $allowedExtensions) {
                        foreach ($images as $img) {
                            $ext = strtolower($img->getClientOriginalExtension());
                            if (($img->getSize() / 1000000) > 2) {
                                throw ValidationException::withMessages(['attachments' => "Images MAX  2MB ALLOW!"]);
                            }
                            if (!in_array($ext, $allowedExtensions)) {
                                throw ValidationException::withMessages(['attachments' => "Only png, jpg, jpeg, pdf images are allowed"]);
                            }
                        }
                        if (count($images) > 5) {
                            throw ValidationException::withMessages(['attachments' => "Maximum 5 images can be uploaded"]);
                        }
                    },
                ],
                'message' => 'required',
            ]);

            try {
                $ticket = SupportTicket::findOrFail($id);
                $ticket->update([
                    'status' => 2,
                    'last_reply' => Carbon::now()
                ]);

                $message = SupportTicketMessage::create([
                    'support_ticket_id' => $ticket->id,
                    'message' => $request->message
                ]);

                if (!empty($request->attachments)) {
                    $numberOfAttachments = count($request->attachments);
                    for ($i = 0; $i < $numberOfAttachments; $i++) {
                        if ($request->hasFile('attachments.' . $i)) {
                            $file = $request->file('attachments.' . $i);
                            $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null, null, 'webp', null, null, null);
                            if (empty($supportFile['path'])) {
                                throw new \Exception('File could not be uploaded.');
                            }
                            $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                        }
                    }
                }

                $msg = [
                    'username' => optional($ticket->user)->username,
                    'ticket_id' => $ticket->ticket
                ];
                $action = [
                    "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
                    "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
                    "link" => route('admin.ticket.view', $ticket->id),
                    "icon" => "fas fa-ticket-alt text-white"
                ];

                $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);
                return back()->with('success', 'Ticket has been replied');
            } catch (\Exception $exception) {
                return back()->with('error', $exception->getMessage());
            }

        } elseif ($request->replayTicket == 2) {
            $ticket = SupportTicket::findOrFail($id);
            $ticket->update([
                'status' => 3,
                'last_reply' => Carbon::now()
            ]);

            return back()->with('success', 'Ticket has been closed');
        }
        return back();
    }


    public function download($ticket_id)
    {
        $attachment = SupportTicketAttachment::with('supportMessage', 'supportMessage.ticket')->findOrFail(decrypt($ticket_id));
        $file = $attachment->file;
        $full_path = getFile($attachment->driver, $file);
        $title = slug($attachment->supportMessage->ticket->subject) . '-' . $file;
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $full_path);
        return readfile($full_path);
    }


    public function newTicketValidation(Request $request): void
    {
        $images = $request->file('attachments');
        $allowedExtension = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($images, $allowedExtension) {
                    foreach ($images as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            throw ValidationException::withMessages(['attachments' => "Images MAX  2MB ALLOW!"]);
                        }
                        if (!in_array($ext, $allowedExtension)) {
                            throw ValidationException::withMessages(['attachments' => "Only png, jpg, jpeg, pdf images are allowed"]);
                        }
                    }
                    if (count($images) > 5) {
                        throw ValidationException::withMessages(['attachments' => "Maximum 5 images can be uploaded"]);
                    }
                },
            ],
            'subject' => 'required|max:100',
            'message' => 'required'
        ]);
    }


    public function saveTicket(Request $request, $random)
    {
        try {
            $ticket = SupportTicket::create([
                'user_id' => auth()->id(),
                'ticket' => $random,
                'subject' => $request->subject,
                'status' => 0,
                'last_reply' => Carbon::now(),
            ]);

            if (!$ticket) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return $ticket;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function saveMsgTicket(Request $request, $ticket)
    {
        try {
            $message = SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'message' => $request->message
            ]);

            if (!$message) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return $message;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function saveAttachment($message, $path, $driver)
    {
        try {
            $attachment = SupportTicketAttachment::create([
                'support_ticket_message_id' => $message->id,
                'file' => $path ?? null,
                'driver' => $driver ?? 'local',
            ]);

            if (!$attachment) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return true;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

}
