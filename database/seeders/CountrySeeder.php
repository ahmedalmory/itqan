<?php

namespace database\seeders;

use illuminate\database\seeder;
use illuminate\support\facades\db;

class countryseeder extends seeder
{
    /**
     * run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'id' => '1',
                'name' => 'Egypt',
                'alt_name' => 'مصر',
                'order' => 1,
                'country_code' => 20,
            ],
            [
                'id' => '2',
                'name' => 'Saudi Arabia',
                'alt_name' => 'المملكة العربية السعودية',
                'order' => 2,
                'country_code' => 966,
            ],
            [
                'id' => '3',
                'name' => 'United Arab Emirates',
                'alt_name' => 'الإمارات العربية المتحدة',
                'order' => 3,
                'country_code' => 971,
            ],
            [
                'id' => '4',
                'name' => 'Kuwait',
                'alt_name' => 'الكويت',
                'order' => 4,
                'country_code' => 965,
            ],
            [
                'id' => '5',
                'name' => 'Qatar',
                'alt_name' => 'قطر',
                'order' => 5,
                'country_code' => 974,
            ],
            [
                'id' => '6',
                'name' => 'Oman',
                'alt_name' => 'عمان',
                'order' => 6,
                'country_code' => 968,
            ],
            [
                'id' => '7',
                'name' => 'Bahrain',
                'alt_name' => 'البحرين',
                'order' => 7,
                'country_code' => 973,
            ],
            [
                'id' => '8',
                'name' => 'Jordan',
                'alt_name' => 'الأردن',
                'order' => 8,
                'country_code' => 962,
            ],
            [
                'id' => '9',
                'name' => 'Lebanon',
                'alt_name' => 'لبنان',
                'order' => 9,
                'country_code' => 961,
            ],
            [
                'id' => '10',
                'name' => 'Iraq',
                'alt_name' => 'العراق',
                'order' => 10,
                'country_code' => 964,
            ],
            [
                'id' => '11',
                'name' => 'Syria',
                'alt_name' => 'سوريا',
                'order' => 11,
                'country_code' => 963,
            ],
            [
                'id' => '12',
                'name' => 'Palestine',
                'alt_name' => 'فلسطين',
                'order' => 12,
                'country_code' => 970,
            ],
            [
                'id' => '13',
                'name' => 'Yemen',
                'alt_name' => 'اليمن',
                'order' => 13,
                'country_code' => 967,
            ],
            [
                'id' => '14',
                'name' => 'Libya',
                'alt_name' => 'ليبيا',
                'order' => 14,
                'country_code' => 218,
            ],
            [
                'id' => '15',
                'name' => 'Sudan',
                'alt_name' => 'السودان',
                'order' => 15,
                'country_code' => 249,
            ],
            [
                'id' => '16',
                'name' => 'Morocco',
                'alt_name' => 'المغرب',
                'order' => 16,
                'country_code' => 212,
            ],
            [
                'id' => '17',
                'name' => 'Algeria',
                'alt_name' => 'الجزائر',
                'order' => 17,
                'country_code' => 213,
            ],
            [
                'id' => '18',
                'name' => 'Tunisia',
                'alt_name' => 'تونس',
                'order' => 18,
                'country_code' => 216,
            ],
            [
                'id' => '19',
                'name' => 'Mauritania',
                'alt_name' => 'موريتانيا',
                'order' => 19,
                'country_code' => 222,
            ],
            [
                'id' => '20',
                'name' => 'Somalia',
                'alt_name' => 'الصومال',
                'order' => 20,
                'country_code' => 252,
            ],
            [
                'id' => '21',
                'name' => 'Djibouti',
                'alt_name' => 'جيبوتي',
                'order' => 21,
                'country_code' => 253,
            ],
            [
                'id' => '22',
                'name' => 'Comoros',
                'alt_name' => 'جزر القمر',
                'order' => 22,
                'country_code' => 269,
            ],
        ];

        DB::table('countries')->insert($countries);
    }
} 