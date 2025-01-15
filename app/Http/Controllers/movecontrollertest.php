            // dd($receipt->receipt_items);
            //TAX
            // $total_tax = $receipt->receipt_items->total_tax;
            // dd($receipt->receipt_items->total->valueNumber);

            if(isset($receipt->receipt_items->TotalTax)){
                if(isset($receipt->receipt_items->TotalTax->valueCurrency)){
                    $total_tax = $ocr_receipt_extract_prefix['TotalTax']['valueCurrency']['amount'];
                }elseif(isset($ocr_receipt_extract_prefix['TotalTax']['valueNumber'])){
                    $total_tax = $ocr_receipt_extract_prefix['TotalTax']['valueNumber'];
                }else{
                    $total_tax = NULL;
                }
            }else{
                $total_tax = NULL;
            }

            //SUBTOTAL
            if(isset($receipt->receipt_items->subtotal)){
                if(isset($receipt->receipt_items->subtotal->valueCurrency)){
                    $subtotal = $receipt->receipt_items->subtotal->valueCurrency->amount;
                }elseif(isset($receipt->receipt_items->subtotal->valueNumber)){
                    $subtotal = $receipt->receipt_items->subtotal->valueNumber;
                }else{
                    $subtotal = NULL;
                }
            }else{
                $subtotal = NULL;
            }

            //AMOUNT
            if(isset($receipt->receipt_items->total)){
                if(isset($receipt->receipt_items->total->valueCurrency)){
                    $amount = $receipt->receipt_items->total->valueCurrency->amount;
                }elseif(isset($receipt->receipt_items->total->valueNumber)){
                    $amount = $receipt->receipt_items->total->valueNumber;
                }else{
                    $amount = NULL;
                }
            }elseif(isset($receipt->receipt_items->InvoiceTotal)){
                $amount = $receipt->receipt_items->InvoiceTotal->valueCurrency->amount;
            }elseif(isset($receipt->receipt_items->SubTotal) && isset($receipt->receipt_items->TotalTax)){
                $amount = $receipt->receipt_items->SubTotal->valueCurrency->amount + $receipt->receipt_items->TotalTax->valueCurrency->amount;
            }else{
                $amount = NULL;
            }

            $merchant_name = $receipt->receipt_items->merchant_name;
            $transaction_date = $receipt->receipt_items->transaction_date;

            $invoice_number = $receipt->receipt_items->invoice_number ?? NULL;
            $purchase_order = $receipt->receipt_items->purchase_order ?? NULL;
            $handwritten_notes = $receipt->receipt_items->handwritten_notes ?? NULL;

            $formatted_items = [];
            foreach($receipt->receipt_items->items as $line_item){
                $formatted_items[$key]['Description'] = isset($line_item->valueObject->Description->valueString) ? $line_item->valueObject->Description->valueString : NULL;
                $formatted_items[$key]['ProductCode'] = isset($line_item->valueObject->ProductCode) ? $line_item->valueObject->ProductCode->valueString : NULL;

                if(isset($line_item->valueObject->TotalPrice)){
                    $formatted_items[$key]['TotalPrice'] = $line_item->valueObject->TotalPrice->valueNumber;
                }else{
                    if(isset($line_item->valueObject->TotalPrice)){
                        $formatted_items[$key]['TotalPrice'] = $line_item->valueObject->TotalPrice->valueNumber;
                    }elseif(isset($line_item->valueObject->Amount)){
                        $formatted_items[$key]['TotalPrice'] = $line_item->valueObject->Amount->valueCurrency->amount;
                    }else{
                        $formatted_items[$key]['TotalPrice'] = NULL;
                    }
                }

                //quantity
                if(isset($line_item->valueObject->Quantity)){
                    $formatted_items[$key]['Quantity'] = $line_item->valueObject->Quantity->valueNumber;
                }else{
                    $formatted_items[$key]['Quantity'] = 1;
                }

                //price each
                if(isset($line_item->valueObject->Price)){
                    if(isset($line_item->valueObject->Price->valueNumber)){
                        $formatted_items[$key]['Price'] = $line_item->valueObject->Price->valueNumber;
                    }elseif(isset($line_item->valueObject->Price->valueCurrency)){
                        $formatted_items[$key]['Price'] = $line_item->valueObject->Price->valueCurrency->amount;
                    }
                }else{
                    $formatted_items[$key]['Price'] = $formatted_items[$key]['TotalPrice'];
                }


                // $receipt->receipt_items->items = $formatted_items;
                // $receipt->save();
            }



            $receipt->receipt_items = [
                'items' => $formatted_items,
                'subtotal' => $subtotal,
                'total' => $amount,
                'total_tax' => $total_tax,
                'transaction_date' => $transaction_date,
                'merchant_name' => $merchant_name,
                'invoice_number' => $invoice_number,
                'merchant_name' => $merchant_name,
                'purchase_order' => $purchase_order,
                'handwritten_notes' => $handwritten_notes,
            ];


            // dd($receipt->receipt_items);
            // [$formatted_items, $amount, $subtotal, $total_tax, $merchant_name, $transaction_date];
            $receipt->save();
            // dd($receipt);








            foreach($receipts as $key => $receipt){
            if(!is_null($receipt->receipt_items->items)){
                dd($receipt->receipt_items);
                foreach($receipt->receipt_items->items as $item){
                    $this_item = collect();
                    if(isset($item->valueObject->Price->valueNumber)){
                        $this_item->price_each = $item->valueObject->Price->valueNumber;
                    }elseif(isset($item->valueObject->Price->valueCurrency)){
                        $this_item->price_each = $item->valueObject->Price->valueCurrency->amount;
                    }elseif(isset($item->valueObject->UnitPrice->valueCurrency)){
                        $this_item->price_each = $item->valueObject->UnitPrice->valueCurrency->amount;
                    }else{
                        $this_item->price_each = NULL;
                    }

                    if(isset($item->valueObject->TotalPrice->valueNumber)){
                        $this_item->price_total = $item->valueObject->TotalPrice->valueNumber;
                    }elseif(isset($item->valueObject->TotalPrice->valueCurrency)){
                        $this_item->price_total = $item->valueObject->TotalPrice->valueCurrency->amount;
                    }elseif(isset($item->valueObject->TotalPrice->valueNumber)){
                        $this_item->price_total = $item->valueObject->TotalPrice->valueNumber;
                    }elseif(isset($item->valueObject->Amount)){
                        $this_item->price_total = $item->valueObject->Amount->valueCurrency->amount;
                    }else{
                        $this_item->price_total = NULL;
                    }

                    if(isset($item->valueObject->Quantity->valueNumber)){
                        $this_item->quantity = $item->valueObject->Quantity->valueNumber;
                    }else{
                        if($item->price_each == $item->price_total){
                            $this_item->quantity = 1;
                        }else{
                            if(!is_null($item->price_total) && !is_null($item->price_each)){
                                $this_item->quantity = $item->price_total / $item->price_each;
                            }else{
                                $this_item->quantity = 1;
                            }
                        }
                    }

                    if(isset($item->valueObject->Description)){
                        $this_item->desc = $item->valueObject->Description->valueString;
                    }else{
                        $this_item->desc = $item->content;
                    }

                    $this_item->product_code = isset($item->valueObject->ProductCode->valueString) ? '# ' . $item->valueObject->ProductCode->valueString : NULL;

                    dd($this_item);
                }
            }
        }
