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
use App\Models\RichText;
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
	
	public static function sendToUser(User $user, $title, $content, $attr = '{}', $source_id = 1, $from_user_id = 0)
	{
	
		$message_content =  MessageContent::create(
			["title" => $title, "source_id" => $source_id,
				"attr" => $attr, "sendtype" => 0]);
		
		
		$richtext = new RichText(["content" => $content]);
		$message = new Message([
			"attr" => $attr,
			"status" => 1,
			"to_user_id" => $user->id,
			"from_user_id" => $from_user_id,
		]);
		$message_content->content()->save($richtext);
		$message_content->message()->save($message);
//		$richtext->item()->create();
//		$message->save();
		return [$message,$message->content(),$message_content->content()];
	}
	
	public static function getGroupMessages(Authenticatable $user)
	{
		$message_contents = MessageContent::query()
			->where('created_at', '>=', $user->created_at)->get();//用户注册后的群发消息
		$messages = [];
		foreach ($message_contents as $content) {
			$content_id = $content->id;
			$message = Message::query()
				->firstOrCreate([
					'content_id' => $content_id, 'to_user_id' => $user->id],
					['content_id' => $content_id, 'attr' => '',
						'status' => 0, 'form_user_id' => 0,
						'to_user_id' => $user->id, 'created_at' => $content->created_at
					]);
			array_push($messages, $message);
		}
		return $messages;
	}
	
	public static function getSender(Message $message)
	{
		$sender = User::find($message->form_user_id);
		if (!$sender) {
			$sender = $message->content()->first()
				->source()->first();
		}
		return $sender->name;
	}
}