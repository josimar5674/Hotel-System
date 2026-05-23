<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pais;

class PaisSeeder extends Seeder
{
    public function run(): void
    {
        $paises = [

            ['nombre'=>'Honduras','nacionalidad'=>'Hondureña','codigo'=>'HN','bandera'=>'🇭🇳'],
            ['nombre'=>'Estados Unidos','nacionalidad'=>'Estadounidense','codigo'=>'US','bandera'=>'🇺🇸'],

            ['nombre'=>'Guatemala','nacionalidad'=>'Guatemalteca','codigo'=>'GT','bandera'=>'🇬🇹'],
            ['nombre'=>'El Salvador','nacionalidad'=>'Salvadoreña','codigo'=>'SV','bandera'=>'🇸🇻'],
            ['nombre'=>'Nicaragua','nacionalidad'=>'Nicaragüense','codigo'=>'NI','bandera'=>'🇳🇮'],
            ['nombre'=>'Costa Rica','nacionalidad'=>'Costarricense','codigo'=>'CR','bandera'=>'🇨🇷'],
            ['nombre'=>'Panamá','nacionalidad'=>'Panameña','codigo'=>'PA','bandera'=>'🇵🇦'],
            ['nombre'=>'México','nacionalidad'=>'Mexicana','codigo'=>'MX','bandera'=>'🇲🇽'],
            ['nombre'=>'Canadá','nacionalidad'=>'Canadiense','codigo'=>'CA','bandera'=>'🇨🇦'],
            ['nombre'=>'Colombia','nacionalidad'=>'Colombiana','codigo'=>'CO','bandera'=>'🇨🇴'],
            ['nombre'=>'Venezuela','nacionalidad'=>'Venezolana','codigo'=>'VE','bandera'=>'🇻🇪'],
            ['nombre'=>'Argentina','nacionalidad'=>'Argentina','codigo'=>'AR','bandera'=>'🇦🇷'],
            ['nombre'=>'Brasil','nacionalidad'=>'Brasileña','codigo'=>'BR','bandera'=>'🇧🇷'],
            ['nombre'=>'Chile','nacionalidad'=>'Chilena','codigo'=>'CL','bandera'=>'🇨🇱'],
            ['nombre'=>'Perú','nacionalidad'=>'Peruana','codigo'=>'PE','bandera'=>'🇵🇪'],
            ['nombre'=>'Ecuador','nacionalidad'=>'Ecuatoriana','codigo'=>'EC','bandera'=>'🇪🇨'],
            ['nombre'=>'Bolivia','nacionalidad'=>'Boliviana','codigo'=>'BO','bandera'=>'🇧🇴'],
            ['nombre'=>'Paraguay','nacionalidad'=>'Paraguaya','codigo'=>'PY','bandera'=>'🇵🇾'],
            ['nombre'=>'Uruguay','nacionalidad'=>'Uruguaya','codigo'=>'UY','bandera'=>'🇺🇾'],
            ['nombre'=>'España','nacionalidad'=>'Española','codigo'=>'ES','bandera'=>'🇪🇸'],
            ['nombre'=>'Francia','nacionalidad'=>'Francesa','codigo'=>'FR','bandera'=>'🇫🇷'],
            ['nombre'=>'Italia','nacionalidad'=>'Italiana','codigo'=>'IT','bandera'=>'🇮🇹'],
            ['nombre'=>'Alemania','nacionalidad'=>'Alemana','codigo'=>'DE','bandera'=>'🇩🇪'],
            ['nombre'=>'Portugal','nacionalidad'=>'Portuguesa','codigo'=>'PT','bandera'=>'🇵🇹'],
            ['nombre'=>'Reino Unido','nacionalidad'=>'Británica','codigo'=>'GB','bandera'=>'🇬🇧'],
            ['nombre'=>'Irlanda','nacionalidad'=>'Irlandesa','codigo'=>'IE','bandera'=>'🇮🇪'],
            ['nombre'=>'Suiza','nacionalidad'=>'Suiza','codigo'=>'CH','bandera'=>'🇨🇭'],
            ['nombre'=>'Países Bajos','nacionalidad'=>'Neerlandesa','codigo'=>'NL','bandera'=>'🇳🇱'],
            ['nombre'=>'Bélgica','nacionalidad'=>'Belga','codigo'=>'BE','bandera'=>'🇧🇪'],
            ['nombre'=>'Suecia','nacionalidad'=>'Sueca','codigo'=>'SE','bandera'=>'🇸🇪'],
            ['nombre'=>'Noruega','nacionalidad'=>'Noruega','codigo'=>'NO','bandera'=>'🇳🇴'],
            ['nombre'=>'Dinamarca','nacionalidad'=>'Danesa','codigo'=>'DK','bandera'=>'🇩🇰'],
            ['nombre'=>'Finlandia','nacionalidad'=>'Finlandesa','codigo'=>'FI','bandera'=>'🇫🇮'],
            ['nombre'=>'Rusia','nacionalidad'=>'Rusa','codigo'=>'RU','bandera'=>'🇷🇺'],
            ['nombre'=>'China','nacionalidad'=>'China','codigo'=>'CN','bandera'=>'🇨🇳'],
            ['nombre'=>'Japón','nacionalidad'=>'Japonesa','codigo'=>'JP','bandera'=>'🇯🇵'],
            ['nombre'=>'Corea del Sur','nacionalidad'=>'Surcoreana','codigo'=>'KR','bandera'=>'🇰🇷'],
            ['nombre'=>'India','nacionalidad'=>'India','codigo'=>'IN','bandera'=>'🇮🇳'],
            ['nombre'=>'Australia','nacionalidad'=>'Australiana','codigo'=>'AU','bandera'=>'🇦🇺'],
            ['nombre'=>'Nueva Zelanda','nacionalidad'=>'Neozelandesa','codigo'=>'NZ','bandera'=>'🇳🇿'],
            ['nombre'=>'Sudáfrica','nacionalidad'=>'Sudafricana','codigo'=>'ZA','bandera'=>'🇿🇦'],
            ['nombre'=>'Egipto','nacionalidad'=>'Egipcia','codigo'=>'EG','bandera'=>'🇪🇬'],
            ['nombre'=>'Turquía','nacionalidad'=>'Turca','codigo'=>'TR','bandera'=>'🇹🇷'],
            ['nombre'=>'Israel','nacionalidad'=>'Israelí','codigo'=>'IL','bandera'=>'🇮🇱'],

        ];


        foreach($paises as $pais) {

            Pais::create($pais);

        }
    }
}