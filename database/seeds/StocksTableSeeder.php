<?php

use Illuminate\Database\Seeder;
use App\Stock;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'AFL',
            'company_name' => 'Aflac, Inc',
            'logo' => "",
            'website' => 'http://investors.aflac.com',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'AMZN',
            'company_name' => 'Amazon Com Inc Com',
            'logo' => '',
            'website' => 'http://http://phx.corporate-ir.net/phoenix.zhtml?c=97664&p=irol-irhome',
            'dividend' => false,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'APPL',
            'company_name' => 'Apple Inc Com',
            'logo' => '',
            'website' => 'http://www.apple.com',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'BAC',
            'company_name' => 'Bank of America Corporation',
            'logo' => '',
            'website' => 'http://investor.bankofamerica.com/phoenix.zhtml?c=71595&p=irol-stockquote&cm_re=EBZ-Corp_SocialResponsibility-_-About_Us-_-EI38LT000F_About_Us_Stock_Quote#fbid=mjOTgzgnn2d',
            'dividend' => true,
        ]);


        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'CIEN',
            'company_name' => 'Ciena',
            'logo' => '',
            'website' => 'http://www.ciena.com/investors',
            'dividend' => false,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'CSCO',
            'company_name' => 'Cisco Systems, Inc',
            'logo' => '',
            'website' => 'http://investor.cisco.com/investor-relations/overview/default.aspx',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'DIS',
            'company_name' => 'Walt Disney Company',
            'logo' => '',
            'website' => 'https://thewaltdisneycompany.com/investor-relations/',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'EL',
            'company_name' => 'Estee Lauder Companies Inc.',
            'logo' => '',
            'website' => 'http://www.elcompanies.com/investors',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'FB',
            'company_name' => 'Facebook, Inc.',
            'logo' => '',
            'website' => 'https://www.facebook.com/',
            'dividend' => false,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'FDX',
            'company_name' => 'FedEx Corporation',
            'logo' => '',
            'website' => 'http://investors.fedex.com/investor-home/default.aspx',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'GD',
            'company_name' => 'General Dynamics Corporation',
            'logo' => '',
            'website' => 'http://investorrelations.gd.com',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'HD',
            'company_name' => 'Home Depot, Inc',
            'logo' => '',
            'website' => 'http://ir.homedepot.com/',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'IBM',
            'company_name' => 'International Business Machines Corporation',
            'logo' => '',
            'website' => 'https://www.ibm.com/investor/?lnk=fab',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'JNJ',
            'company_name' => 'Johnson and Johnson',
            'logo' => '',
            'website' => 'http://www.investor.jnj.com/',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'K',
            'company_name' => 'Kellogg Company',
            'logo' => '',
            'website' => 'http://investor.kelloggs.com/',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'LB',
            'company_name' => 'L Brands Inc',
            'logo' => '',
            'website' => 'http://investors.lb.com/phoenix.zhtml?c=94854&p=irol-irHome',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'MCD',
            'company_name' => "McDonald's Corporation",
            'logo' => '',
            'website' => 'http://corporate.mcdonalds.com/mcd/investors.html',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'NFLX',
            'company_name' => 'NetFlix, Inc',
            'logo' => '',
            'website' => 'https://ir.netflix.com/index.cfm',
            'dividend' => false,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'OC',
            'company_name' => 'Owens Corning',
            'logo' => '',
            'website' => 'http://investor.owenscorning.com/investors/overview/default.aspx',
            'dividend' => true,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'PCLN',
            'company_name' => 'Priceline Group Inc.',
            'logo' => '',
            'website' => 'http://ir.pricelinegroup.com',
            'dividend' => false,
        ]);

        Stock::insert([
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            'ticker' => 'QSR',
            'company_name' => 'Restaurant Brands International',
            'logo' => '',
            'website' => 'http://www.rbi.com/Investor-Home',
            'dividend' => true,
        ]);
    }
}
