<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/8/29
 * Time: 15:55
 */

namespace App\Components;


use App\Models\Message;
use App\Models\MessageContent;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class MessageManager extends Manager
{
	
	protected static $keys = ['parentid', 'region_name', 'have_children', 'order'];
	
	protected static $primary_key = 'id';
	
	protected static $Modle = Message::class;
	
	public static function getByUserId()
	{
	
	}
	
	public static function getGroupMessages(Authenticatable $user)
	{
		$message_content_ids = MessageContent::query()
			->where('created_at', '>=', $user->created_at);//用户注册后的群发消息
		$messages = [];
		foreach ($message_content_ids as $content) {
			$content_id=$content->id;
			$message = Message::query()
				->firstOrCreate([
					'content_id' => $content_id, 'to_user_id' => $user->id],
					['content_id' => $content_id, 'attr' => '',
						'status' => 0, 'form_user_id' => 0,
						'to_user_id' => $user->id, 'created_at'=>$content->created_at
					]);
			array_push($messages,$message);
		}
		return $messages;
	}
	
	public static function getSender(Message $message){
		$sender=User::find($message->form_user_id);
		if(!$sender){
			$sender=$message->content()->first()
				->source()->first();
		}
		return $sender->name;
	}
}