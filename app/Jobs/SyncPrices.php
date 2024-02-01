<?php

namespace App\Jobs;

use App\Models\Security;
use App\Models\SecurityPrice;
use App\Models\SecurityType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SyncPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $securityTypes = SecurityType::all();
        
        foreach($securityTypes as $securityType)
        {
                $result  = $this->consultarAPI($securityType->name);
                foreach ($result as $value) {

                    $security = Security::where('symbol', '=', $value['symbol'])->first();

                    if ($security) {

                        $dateTime = new \DateTime($value['last_price_datetime'], new \DateTimeZone('UTC'));
                        $formattedDate = $dateTime->format('Y-m-d H:i:s');                
                        SecurityPrice::updateOrCreate(
                            ['security_id' => $security->id],
                            ['last_price' => $value['price'], 'as_of_date' => $formattedDate]
                        );
                    }
                
                }
            }
            
    }

    function consultarAPI($security_type) {
        $datosSimulados = [
            [
                "security_type" => "mutual_funds",
                "symbol" => "APPL",
                "price" => 1800,
                "last_price_datetime" => "2023-10-30T17:31:18-04:00"
            ],
            [
                "security_type" => "mutual_funds",
                "symbol" => "TSLA",
                "price" => 21555.98,
                "last_price_datetime" => "2023-10-30T17:32:19-04:00"
            ],
            [
                "security_type" => "mutual_funds",
                "symbol" => "AMZN",
                "price" => 321.45,
                "last_price_datetime" => "2023-10-30T17:33:21-04:00"
            ],
            [
                "security_type" => "ETF",
                "symbol" => "QQQ",
                "price" => 350.67,
                "last_price_datetime" => "2023-10-30T17:34:30-04:00"
            ],
            [
                "security_type" => "ETF",
                "symbol" => "SPY",
                "price" => 400.21,
                "last_price_datetime" => "2023-10-30T17:35:45-04:00"
            ],
            [
                "security_type" => "ETF",
                "symbol" => "IWM",
                "price" => 150.54,
                "last_price_datetime" => "2023-10-30T17:36:50-04:00"
            ]
        ];
    
        $resultadosFiltrados = array_filter($datosSimulados, function ($item) use ($security_type) {
            return $item['security_type'] === $security_type;
        });
    
        return array_values($resultadosFiltrados); 
    }
    
 
    
}
