<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Traits\Upload;
use App\Traits\Notify;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SupportTicketController extends Controller
{
    use Upload, Notify;

    public function tickets($status = 'all')
    {
        $ticketRecord = \Cache::get('ticketRecord');
        if (!$ticketRecord){
            $ticketRecord = SupportTicket::selectRaw('COUNT(id) AS totalTicket')
                ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS openTicket')
                ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS openTicketPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS answerTicket')
                ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS answerTicketPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 2 THEN id END) AS repliedTicket')
                ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS repliedTicketPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 3 THEN id END) AS closedTicket')
                ->selectRaw('(COUNT(CASE WHEN status = 3 THEN id END) / COUNT(id)) * 100 AS closedTicketPercentage')
                ->get()
                ->toArray();
            \Cache::put('ticketRecord', $ticketRecord);
        }
        return view('admin.support_ticket.list', compact('status', 'ticketRecord'));
    }


    public function ticketSearch(Request $request, $status)
    {
        $filterSubject = $request->subject;
        $filterStatus = $request->filterStatus;
        $search = $request->search['value'];
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $supportTicket = SupportTicket::query()->with('user:id,username,firstname,lastname,image,image_driver')
            ->whereHas('user')
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('subject', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%");
                            $q->orWhere('lastname', 'LIKE', "%$search%");
                            $q->orWhere('username', 'LIKE', "%$search%");
                            $q->orWhere('email', 'LIKE', "%$search%");
                        });
                });
            })
            ->when(!empty($filterSubject), function ($query) use ($filterSubject) {
                return $query->where('subject', $filterSubject);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($supportTicket)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('username', function ($item) {
                $url = route("admin.user.edit", optional($item->user)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . optional($item->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('subject', function ($item) {
                return Str::limit($item->subject, 30);

            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                    <span class="legend-indicator bg-warning"></span> ' . trans('Open') . '
                                </span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                    <span class="legend-indicator bg-success"></span> ' . trans('Answered') . '
                                </span>';

                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-info text-info">
                                    <span class="legend-indicator bg-info"></span> ' . trans('Customer Reply') . '
                                </span>';

                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">
                                    <span class="legend-indicator bg-danger"></span> ' . trans('Closed') . '
                                </span>';
                }
            })
            ->addColumn('lastReply', function ($item) {
                return dateTime($item->last_reply);
            })
            ->addColumn('action', function ($item) {
                $url = route('admin.ticket.view', $item->id);
                return '<a class="btn btn-white btn-sm" href="' . $url . '">
                      <i class="bi-eye"></i> ' . trans("View") . '
                    </a>';
            })
            ->rawColumns(['username', 'subject', 'status', 'action'])
            ->make(true);
    }


    public function ticketView($id)
    {
        $data['ticket'] = SupportTicket::where('id', $id)->with('user', 'messages')->firstOrFail();
        $data['title'] = "Ticket #" . $data['ticket']->ticket;
        return view('admin.support_ticket.view', $data);
    }


    public function ticketReplySend(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $admin = Auth::guard('admin')->user();

            $ticketRes = SupportTicket::where('id', $id)->firstOr(function () {
                throw new \Exception('No data found!');
            });

            $ticketRes->update([
                'last_reply' => Carbon::now(),
                'status' => 1,
            ]);

            if (!$ticketRes) {
                DB::rollBack();
                throw new Exception('Something went wrong while updating data.');
            }

            $resTicketDetails = SupportTicketMessage::create([
                'support_ticket_id' => $id,
                'admin_id' => $admin->id,
                'message' => $request->message,
            ]);

            if (!$resTicketDetails) {
                DB::rollBack();
                throw new Exception('Something went wrong while updating data.');
            }

            if (!empty($request->attachments)) {
                $attachments = [];
                $numberOfAttachments = count($request->attachments);

                for ($i = 0; $i < $numberOfAttachments; $i++) {
                    if ($request->hasFile('attachments.' . $i)) {
                        $file = $request->file('attachments.' . $i);
                        $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null,null,'webp',80);
                        if (empty($supportFile['path'])) {
                            throw new Exception('File could not be uploaded.');
                        }
                        $attachments[] = [
                            'support_ticket_message_id' => $resTicketDetails->id,
                            'file' => $supportFile['path'],
                            'driver' => $supportFile['driver'] ?? 'local',
                        ];
                    }
                }
            }

            if (!empty($attachments)) {
                $attachmentResponse = DB::table('support_ticket_attachments')->insert($attachments);
                if (!$attachmentResponse) {
                    DB::rollBack();
                    throw new Exception('Something went wrong while storing attachments. Please try again later.');
                }
            }

            DB::commit();

            $msg = [
                'ticket_id' => $ticketRes->ticket
            ];
            $action = [
                "link" => route('user.ticket.view', $ticketRes->ticket),
                "icon" => "fas fa-ticket-alt text-white"
            ];

            $this->userPushNotification($ticketRes->user, 'ADMIN_REPLIED_TICKET', $msg, $action);
            $this->userFirebasePushNotification($ticketRes->user, 'ADMIN_REPLIED_TICKET', $msg, $action);
            $this->sendMailSms($ticketRes->user, 'ADMIN_REPLIED_TICKET', [
                'ticket_id' => $ticketRes->ticket,
                'ticket_subject' => $ticketRes->subject,
                'reply' => $request->message,
            ]);

            return back()->with('success', "Ticket has been replied");

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

    }

    public function ticketClosed($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'status' => 3
        ]);
        return back()->with('success', "Ticket has been closed");
    }

    public function ticketDownload($ticket_id)
    {
        $attachment = SupportTicketAttachment::with('supportMessage', 'supportMessage.ticket')->findOrFail(decrypt($ticket_id));
        $file = $attachment->file;
        $full_path = getFile($attachment->driver, $file);
        $title = slug($attachment->supportMessage->ticket->subject) . '-' . $file;
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $full_path);
        return readfile($full_path);
    }


}
