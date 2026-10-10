<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketMessage;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupportTicketController extends Controller
{
    use ApiResponse, Notify, Upload;

    public function supportTicketList()
    {
        try {
            $tickets = SupportTicket::where('user_id', Auth::id())->latest()->paginate(basicControl()->paginate);

            $formattedTickets = $tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'user_id' => $ticket->user_id,
                    'ticket_id' => $ticket->ticket,
                    'subject' => $ticket->subject,
                    'status' => ($ticket->status == 0 ? 'Open' : ($ticket->status == 1 ? 'Answered' : ($ticket->status == 2 ? 'Replied' : 'Closed'))),
                    'last_reply' => customDateTime($ticket->last_reply)
                ];
            });

            $tickets->setCollection($formattedTickets);

            return response()->json($this->withSuccess($tickets));

        } catch (\Exception $exception) {
            return response()->json($this->withError($exception->getMessage()));
        }
    }

  /*  public function supportTicketStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $random = rand(100000, 999999);
            $this->newTicketValidation($request);
            $ticket = $this->saveTicket($request, $random);
            $message = $this->saveMsgTicket($request, $ticket);

            if (!empty($request->attachments)) {
                $attachments = is_array($request->attachments) ? $request->attachments : [$request->attachments];
                $numberOfAttachments = count($attachments);
                for ($i = 0; $i < $numberOfAttachments; $i++) {
                    if ($request->hasFile('attachments.' . $i)) {
                        $file = $request->file('attachments.' . $i);
                        $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null, null, 'webp', 90);
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
            DB::commit();
            return response()->json($this->withSuccess('Your Ticket has been pending'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($this->withError($exception->getMessage()));
        }
    }*/


    public function supportTicketView($ticketId)
    {
        $tickets = SupportTicket::with([
            'messages.attachments' => function ($query) {
                $query->select('id', 'support_ticket_message_id', 'file', 'driver');
            },
            'messages.admin' => function ($query) {
                $query->select('id', 'name', 'image', 'image_driver');
            },
            'user' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'image', 'image_driver');
            },
        ])
            ->select('id', 'user_id', 'ticket', 'subject', 'status', 'last_reply')
            ->where('ticket', $ticketId)
            ->get();

        $formattedTicket = $tickets->map(function ($ticket) {
            return [
                'id' => $ticket->id,
                'user_id' => $ticket->user_id,
                'user' => $ticket->user ? [
                    'id' => $ticket->user->id,
                    'name' => "{$ticket->user->firstname} {$ticket->user->lastname}",
                    'image' => getFile($ticket->user->image_driver, $ticket->user->image),
                ] : null,
                'ticket_id' => $ticket->ticket,
                'subject' => $ticket->subject,
                'status' => match ($ticket->status) {
                    0 => 'Open',
                    1 => 'Answered',
                    2 => 'Replied',
                    default => 'Closed',
                },
                'last_reply' => customDateTime($ticket->last_reply),
                'messages' => $ticket->messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'support_ticket_id' => $message->support_ticket_id,
                        'admin_id' => $message->admin_id,
                        'message' => $message->message,
                        'created_at' => customDateTime($message->created_at),
                        'attachments' => $message->attachments->map(fn($attachment) => [
                            'id' => $attachment->id,
                            'support_ticket_message_id' => $attachment->support_ticket_message_id,
                            'file' => getFile($attachment->driver, $attachment->file),
                        ]),
                        'admin' => $message->admin ? [
                            'id' => $message->admin->id,
                            'name' => $message->admin->name,
                            'admin_image' => getFile($message->admin->image_driver, $message->admin->image),
                        ] : null,
                    ];
                }),
            ];
        });

        return response()->json($this->withSuccess($formattedTicket));
    }

    public function supportTicketStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $rules = [
                'attachments.*' => 'image|mimes:jpg,png,jpeg,pdf|max:2048',
                'subject' => 'required|max:100',
                'message' => 'required',
                'attachments' => 'max:5',
            ];
            $message = [
                'attachments.max' => 'Maximum 5 attachments can be uploaded'
            ];
            $validator = Validator::make($request->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }
            $newTicket = SupportTicket::create([
                'user_id' => $user->id,
                'ticket' => rand(100000, 999999),
                'subject' => $request->subject,
                'status' => 0,
                'last_reply' => Carbon::now(),
            ]);
            $ticketMsg = SupportTicketMessage::create([
                'support_ticket_id' => $newTicket->id,
                'message' => $request->message
            ]);

            foreach ($request->file('attachments', []) as $file) {
                $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'),
                    null, null, 'webp', 80);
                if (empty($supportFile['path'])) {
                    return response()->json($this->withError('File could not be uploaded'));
                }
                SupportTicketAttachment::create([
                    'support_ticket_message_id' => $ticketMsg->id,
                    'file' => $supportFile['path'],
                    'driver' => $supportFile['driver'] ?? 'local',
                ]);
            }
            $data = 'Ticket created successfully';
            DB::commit();
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function supportTicketReply(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $ticket = SupportTicket::with('user')->where('ticket' , $id)->first();

            if (!$ticket){
                return response()->json($this->withError('Ticket not found.'));
            }

            $rules = [
                'message' => 'required|string',
                'attachments.*' => 'max:5000|mimes:jpg,png,jpeg,pdf',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }
            $ticket->status = 2;
            $ticket->last_reply = Carbon::now();
            $ticket->save();

            $message = new SupportTicketMessage();
            $message->support_ticket_id = $ticket->id;
            $message->message = $request->message;
            $message->save();

            foreach ($request->file('attachments',[]) as $file) {
                $supportTicketFile = $this->fileUpload($file, config('filelocation.ticket.path'),
                    null, null, 'webp', 80);
                if (empty($supportTicketFile['path'])) {
                    return response()->json($this->withError('File could not be uploaded.'));
                }
                SupportTicketAttachment::create([
                    'support_ticket_message_id' => $message->id,
                    'file' => $supportTicketFile['path'],
                    'driver' => $supportTicketFile['driver'] ?? 'local',
                ]);
            }

            $msg = [
                'username' => optional($ticket->user)->username,
                'ticket_id' => $ticket->ticket
            ];
            $action = [
                "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
                "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
                "link" => route('admin.ticket.view',$ticket->id),
                "icon" => "fas fa-ticket-alt text-white"
            ];
            $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);

            DB::commit();
            return response()->json($this->withSuccess('Ticket has been replied'));

        } catch (\Exception $e){
            DB::rollBack();
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function closeTicket($id)
    {
        try {
            $user = Auth::user();
            $ticket = SupportTicket::find($id);
            if (!$ticket) {
                return response()->json($this->withError('Ticket Not Found.'));
            }
            if ($user->id !== $ticket->user_id) {
                return response()->json($this->withError('Unauthorized. You do not have permission to close this ticket.'));
            }
            if ($ticket->status === 3) {
                return response()->json($this->withError('Ticket is already closed.'));
            }
            $ticket->update([
                'status' => 3,
            ]);
            return response()->json($this->withSuccess('Ticket has been closed'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


    public function newTicketValidation(Request $request): void
    {
        $images = $request->file('attachments');
        $allowedExtension = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($images, $allowedExtension) {
                    $images = is_array($images) ? $images : [$images];
                    foreach ($images as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            return response()->json($this->withError('Images MAX  2MB ALLOW!'));
                        }
                        if (!in_array($ext, $allowedExtension)) {
                            return response()->json($this->withError('Only png, jpg, jpeg, pdf images are allowed'));
                        }
                    }
                    if (count($images) > 5) {
                        return response()->json($this->withError('Maximum 5 images can be uploaded'));
                    }
                },
            ],
            'subject' => 'required|max:100',
            'message' => 'required'
        ]);
    }

    public function saveTicket(Request $request, $random): SupportTicket
    {
        $ticket = new SupportTicket();
        $ticket->user_id = Auth::id();
        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->status = 0;
        $ticket->last_reply = Carbon::now();
        $ticket->save();
        return $ticket;
    }

    public function saveMsgTicket(Request $request, $ticket): SupportTicketMessage
    {
        $message = new SupportTicketMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();
        return $message;
    }

    public function saveAttachment($message, $path, $driver): void
    {
        SupportTicketAttachment::create([
            'support_ticket_message_id' => $message->id,
            'file' => $path ?? null,
            'driver' => $driver ?? 'local',
        ]);
    }

}
