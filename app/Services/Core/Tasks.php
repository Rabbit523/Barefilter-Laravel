<?php

namespace App\Services\Core;

use App\Mail\AutomaticAccount;
use App\Mail\Contact;
use App\Mail\OrderConfirmation;
use App\Mail\TransferOrderConfirmation;
use App\Mail\OrderPlaced;
use App\Mail\OrderSubscriptionNotification;
use App\Mail\TechnicalService;
use App\Mail\UserSignedUp;
use App\Mail\Welcome;
use App\Models\Core\Task;
use App\Models\Core\User;
use App\Models\Orders\Order;
use App\Services\Apis\Cargonizer;
use App\Services\Apis\Netaxept;
use Illuminate\Support\Facades\Mail;

class Tasks
{
    private static $recipients = ["ordre@barefilter.no", "egy6443rw7tn28@print.epsonconnect.com"];
    private static $bcc = ["nohman@fantasylab.io"];
    // private static $recipients = ["aleksei@fantasylab.io"];
    private static $trustpilot_bcc = ["nohman@fantasylab.io", "3582327f1c@invite.trustpilot.com"];
    /*private static $recipients = ["rau@rauxmedia.com", "tatiana.ibanez@rauxmedia.com"];
    private static $bcc = ["projects@rauxmedia.com"];
    , "3582327f1c@invite.trustpilot.com"
     */

    public static function execute()
    {
        $tasks = Task::where('executed', false)
            ->take(5)
            ->get();

        $arr = array();
        foreach ($tasks as $task) {
            array_push($arr, self::run($task));
        }
        return $arr;
    }

    public static function add($type, $properties)
    {
        return Task::create([
            "executed" => false,
            "type_id" => $type,
            "properties" => json_encode($properties),
        ]);
    }

    private static function run($task)
    {
        $response = null;
        switch ($task->type_id) {
            case Task::$welcomeEmail:
                $response = self::sendWelcomeEmail($task);
                break;
            case Task::$orderConfirmationEmail:
                $response = self::sendOrderConfirmationEmail($task);
                break;
            case Task::$transferOrderConfirmationEmail:
                $response = self::sendTransferOrderConfirmationEmail($task);
                break;
            case Task::$automaticSignUpEmail:
                $response = self::sendAutomaticSignUpEmail($task);
                break;
            case Task::$newUserNotificationEmail:
                $response = self::sendNewUserNotificationEmail($task);
                break;
            case Task::$newOrderNotificationEmail:
                $response = self::sendNewOrderNotificationEmail($task);
                break;
            case Task::$orderSubscriptionNotificationEmail:
                $response = self::sendOrderSubscriptionNotificationEmail($task);
                break;
            case Task::$automateNetaxept:
                $response = self::automateNetaxept($task);
                break;
            case Task::$automateCargonizer:
                $response = self::automateCargonizer($task);
                break;
            case Task::$contact:
                $response = self::contact($task);
                break;
            case Task::$bookTechnicalService:
                $response = self::bookTechnicalService($task);
                break;
        }
        return $response;
    }

    private static function sendWelcomeEmail($task)
    {
        $user = User::find($task->properties->user_id);
        Mail::to($user->email)
            ->bcc(self::$bcc)
            ->send(new Welcome($user));
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function sendAutomaticSignUpEmail($task)
    {
        $user = User::find($task->properties->user_id);
        Mail::to($user->email)
            ->bcc(self::$bcc)
            ->send(new AutomaticAccount($user, $task->properties->key));
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function sendNewUserNotificationEmail($task)
    {
        $admin = User::where("role_id", User::$adminRole)->first();
        $user = User::find($task->properties->user_id);
        Mail::to($admin->email)
            ->bcc(self::$bcc)
            ->send(new UserSignedUp($admin, $user));
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function sendOrderConfirmationEmail($task)
    {
        $order = Order::where('id', $task->properties->order_id)
            ->with(["shipping", "billing", "products.product", "products.subscription", "user"])
            ->first();
        if ($order !== null) {
            Mail::to($order->user->email)
                ->bcc(self::$trustpilot_bcc)
                ->send(new OrderConfirmation($order));
        }
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function sendTransferOrderConfirmationEmail($task)
    {
        $order = Order::where('id', $task->properties->order_id)
            ->with(["shipping", "billing", "products.product", "products.subscription", "user"])
            ->first();
        if ($order !== null) {
            Mail::to($order->user->email)
                ->send(new TransferOrderConfirmation($order));
        }
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function sendNewOrderNotificationEmail($task)
    {
        $admin = User::where("role_id", User::$adminRole)->first();
        $order = Order::where('id', $task->properties->order_id)
            ->with(["shipping", "billing", "products.product", "products.subscription", "user"])
            ->first();
        if ($order !== null) {
            Mail::to(self::$recipients)
                ->bcc(self::$bcc)
                ->send(new OrderPlaced($admin, $order));
        }
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function sendOrderSubscriptionNotificationEmail($task)
    {
        $admin = User::where("role_id", User::$adminRole)->first();
        $order = Order::where('id', $task->properties->order_id)
            ->with(["shipping", "billing", "products.product", "products.subscription", "user"])
            ->first();
        if ($order !== null) {
            Mail::to(self::$recipients)
                ->bcc(self::$bcc)
                ->send(new OrderSubscriptionNotification($admin, $order));
        }
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function automateNetaxept($task)
    {
        $result = Netaxept::automate($task->properties->order_id);
        if ($result) {
            $task->executed = true;
            $task->save();
        }
        return $result;
    }

    private static function automateCargonizer($task)
    {
        $result = Cargonizer::automate($task->properties->order_id);
        if ($result) {
            $task->executed = true;
            $task->save();
        }
        return $result;
    }

    private static function contact($task)
    {
        $admin = User::where("role_id", User::$adminRole)->first();
        $recipients = [$admin->email, $task->properties->email];
        Mail::to($recipients)
            ->bcc(self::$bcc)
            ->send(new Contact($task->properties));
        $task->executed = true;
        $task->save();
        return $task;
    }

    private static function bookTechnicalService($task)
    {
        $admin = User::where("role_id", User::$adminRole)->first();
        $recipients = [$admin->email, $task->properties->contact->email];
        Mail::to($recipients)
            ->bcc(self::$bcc)
            ->send(new TechnicalService($task->properties));
        $task->executed = true;
        $task->save();
        return $task;
    }
}
