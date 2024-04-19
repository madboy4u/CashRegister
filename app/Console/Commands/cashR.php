<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\CashRegisterController;

class cashR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cash:Register {fileName=noname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registratore di Cassa, con vendita di prodotti da csv, e stampa dello scontrino.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = storage_path($this->argument('fileName'));

        // extension comnplaiancy
        if ( !Str::endsWith($file, '.csv') ) {
            // Concatenate the extension
            $file = $file . '.csv';
        }

        // check if file exists
        if ( !File::exists($file) ){
            echo 'File Doesn\'t Exist';
            return 0;
        }

        $register = new CashRegisterController();

        // get sales
        $fileContents = File::get($file);

        // parse sales as an array
        $sales = explode("\n", $fileContents);

        foreach ($sales as $sale) {
            $saleData = str_getcsv($sale);
            $register->addItem($saleData[0], $saleData[1]);
        }

        echo $register->printReceipt();

        return 0;
    }
}
