<?php

namespace App\Services;

use DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Models\SOMST;
use App\Models\SODTL;

class ConsumerService
{
    protected $connection;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('MQ_HOST'), 
            env('MQ_PORT'), 
            env('MQ_USER'), 
            env('MQ_PASS'), 
            env('MQ_VHOST')
        );
    }

    // php artisan mq:request-consumer
    public function consumerMessage()
    {
        $channel = $this->connection->channel();
        $channel->queue_declare('request_order_queue', false, true, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            $orderData = json_decode($msg->body, true);

            // Process the order data dalam transaksi
            DB::transaction(function () use ($orderData) {
                $requestSO = $orderData['request_so'];

                // Insert into SOMST
                $somst = SOMST::create([
                    'fc_sono' => $requestSO['fc_sono'],
                    'fc_sotype' => $requestSO['fc_sotype'],
                    'fc_membercode' => $requestSO['fc_membercode'],
                    'fc_membertaxcode' => $requestSO['fc_membertaxcode'],
                    'fd_soexpired' => $requestSO['fd_soexpired'],
                    'fv_member_address_loading' => $requestSO['fv_member_address_loading'],
                    'fn_sodetail' => $requestSO['fn_sodetail'],
                    'fc_status' => $requestSO['fc_status'],
                    'fd_sodate_user' => $requestSO['fd_sodate_user'],
                    'fd_sodate_system' => $requestSO['fd_sodate_system'],
                    'fc_salescode' => $requestSO['fc_salescode'],
                    'fm_disctotal' => $requestSO['fm_disctotal'],
                    'fm_taxvalue' => $requestSO['fm_taxvalue'],
                    'fm_brutto' => $requestSO['fm_brutto'],
                    'fm_netto' => $requestSO['fm_netto'],
                    'fm_downpayment' => $requestSO['fm_downpayment'],
                    'ft_description' => $requestSO['ft_description'],
                    'created_at' => $requestSO['created_at'],
                    'updated_at' => $requestSO['updated_at'],
                    'deleted_at' => $requestSO['deleted_at'],
                    'created_by' => $requestSO['created_by'],
                    'updated_by' => $requestSO['updated_by'],
                    'deleted_by' => $requestSO['deleted_by']
                ]);

                echo " [x] Inserted into SOMST: ", $somst->fc_sono, "\n";

                // Insert into SODTL
                foreach ($requestSO['requestsodetail'] as $detail) {
                    SODTL::create([
                        'fc_sono' => $detail['fc_sono'],
                        'fn_rownum' => $detail['fn_rownum'],
                        'fc_barcode' => $detail['fc_barcode'],
                        'fc_stockcode' => $detail['fc_stockcode'],
                        'fc_statusbonus' => $detail['fc_statusbonus'],
                        'fc_namepack' => $detail['fc_namepack'],
                        'fn_qty' => $detail['fn_qty'],
                        'fn_qty_do' => $detail['fn_qty_do'],
                        'fm_price' => $detail['fm_price'],
                        'fm_discprice' => $detail['fm_discprice'],
                        'fm_value' => $detail['fm_value'],
                        'ft_description' => $detail['ft_description'],
                        'created_at' => $detail['created_at'],
                        'updated_at' => $detail['updated_at'],
                        'created_by' => $detail['created_by'],
                        'updated_by' => $detail['updated_by']
                    ]);
                }

                echo " [x] Inserted into SODTL for SO: ", $requestSO['fc_sono'], "\n";
            });
        };

        $channel->basic_consume('request_order_queue', '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();
    }
}
