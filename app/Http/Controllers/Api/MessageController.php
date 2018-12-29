<?php

namespace App\Http\Controllers\Api;

use App\Components\MessageManager;
use App\Http\Helpers\ApiResponse;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
	public function __construct(\App\Models\Message $datas)
	{
		$this->datas = $datas;
		$this->content = array();
	}
	
	public function getList(Request $request)
	{
		MessageManager::getGroupMessages(Auth::user());
		
		$user=$request->user();
		$messages=Message::query()->where('to_user_id',$user->id)
			->orderBy('status','asc')
			->orderBy('created_at','desc')
			->get();
		foreach ($messages as $message){
            $message->content;
            $message->content->content;
//			$message->content()->with('content')->get();
//                =$message->content()->first()
//				->content->content;
			$message->sender=MessageManager::getSender($message);
		}
		return ApiResponse::makeResponse(true, $messages,ApiResponse::SUCCESS_CODE);
	}

	public function getById(Request $request){
        MessageManager::getGroupMessages(Auth::user());

        $user=$request->user();
        $message=Message::query()->where('to_user_id',$user->id)
            ->orderBy('status','asc')
            ->orderBy('created_at','desc')
            ->with(['content','content.content'])
            ->find($request->get('message_id'));
        return ApiResponse::makeResponse(true, $message,ApiResponse::SUCCESS_CODE);

    }
}
