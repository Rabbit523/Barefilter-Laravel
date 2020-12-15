<?php

namespace App\Services\Orders;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Services\Orders\Orders;
class Exporter
{


    public static function exportToExcel($uid, $sid, $startDate, $endDate){
        $spreadsheet = new Spreadsheet();
        self::writeOrdersSpreadsheet(
            $spreadsheet->getActiveSheet(),
            Orders::getTimeframedHistory($uid, $sid, $startDate, $endDate)
        );
        return self::writeXLSX($spreadsheet);
    }

    private static function writeOrdersSpreadsheet($sheet, $orders){
        $row = 2;
        self::writeSpreadsheetHeader($sheet);
        foreach($orders as $order){
            foreach($order->products as $orderProduct){
                if(self::addProductToSpreadsheetAtRow([
                    "id" => $order->id,
                    "date" =>  $order->created_at,
                    "amount" => $orderProduct->amount,
                    "product" => $orderProduct->product, 
                    "subscription" => $orderProduct->subscription, 
                    "shipping" => $order->shipping, 
                    "billing" => $order->billing,
                    "email" => $order->user->email,
                    "name" => $order->user->first_name . ' ' .  $order->user->last_name,
                    "uid" => $order->user->id
                ], $sheet, $row)){
                    $row++;
                }
            }
        }
    }

    private static function writeSpreadsheetHeader($sheet){
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
        $headings = ["Order ID", "Delivery Month", "Order Date", "Product", "SKU", "Price", "Qty", "Subscription", "Shipping Address", "Billing Address", "User Email", "User Name", "User ID"];
        for($i = 0; $i < count($cols); $i++){
            $sheet->getColumnDimension($cols[$i])->setAutoSize(true);
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $headings[$i]);
        }
    }

    private static function addProductToSpreadsheetAtRow($data, $sheet, $row ){
        $result = false;
        $product = $data["product"];
        $subscription = $data["subscription"];
        if($product !== null && $subscription !== null){
            $result = true;
            $sheet->setCellValueByColumnAndRow(1, $row, $data["id"]);
            $sheet->setCellValueByColumnAndRow(2, $row, self::getDeliveryDates($subscription, $data["date"]));
            $sheet->setCellValueByColumnAndRow(3, $row, $data["date"]);
            $sheet->setCellValueByColumnAndRow(4, $row, $product->name);
            $sheet->setCellValueByColumnAndRow(5, $row, $product->sku);
            $sheet->setCellValueByColumnAndRow(6, $row, $product->price);
            $sheet->setCellValueByColumnAndRow(7, $row, $data["amount"]);
            $sheet->setCellValueByColumnAndRow(8, $row, $subscription->name);
            $sheet->setCellValueByColumnAndRow(9, $row, self::getAddressString($data["shipping"]));
            $sheet->setCellValueByColumnAndRow(10, $row, self::getAddressString($data["billing"]));
            $sheet->setCellValueByColumnAndRow(11, $row, $data["email"]);
            $sheet->setCellValueByColumnAndRow(12, $row, $data["name"]);
            $sheet->setCellValueByColumnAndRow(13, $row, $data["uid"]);

        }
        return $result;
    }

    private static function getDeliveryDates($subscription, $orderDate){
        $date = new \DateTime($orderDate);
        $string = "N/A";
        $format = "F j, Y";
        if($subscription->id === 2){
            $date->modify('+6 month');
            $string = $date->format($format);
        }elseif($subscription->id === 3){
            $date->modify('+6 month');
            $string = $date->format($format);
            $date->modify('+6 month');
            $string .= " and " . $date->format($format);
        }
        return $string;
    }
    private static function getAddressString($address){
        return sprintf('%s %s, %s, %s | %s, %s, %s'
            , isset($address->first_name) ? $address->first_name : 'N/A'
            , isset($address->last_name) ? $address->last_name : 'N/A'
            , isset($address->email) ? $address->email : 'N/A'
            , isset($address->phone) ? $address->phone : 'N/A'
            , isset($address->street_address) ? $address->street_address : 'N/A'
            , isset($address->postal_code) ? $address->postal_code : 'N/A'
            , isset($address->city) ? $address->city : 'N/A'
        );
    }

    private static function writeXLSX($spreadsheet){
        $path = public_path() . '/ordre.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        return $path;
    }
}