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
                    "billing" => $order->billing
                ], $sheet, $row)){
                    $row++;
                }
            }
        }
    }

    private static function writeSpreadsheetHeader($sheet){
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $headings = ["Order ID", "Delivery Month", "Order Date", "Product", "SKU", "Price", "Qty", "Subscription", "Shipping Address", "Billing Address"];
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
            , $address->first_name
            , $address->last_name
            , $address->email
            , $address->phone
            , $address->street_address
            , $address->postal_code
            , $address->city
        );
    }

    private static function writeXLSX($spreadsheet){
        $path = public_path() . '/ordre.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        return $path;
    }
}