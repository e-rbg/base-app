<?php

$data = [
    'Compostela' => [
        'Aurora','Bagongon','Gabi','Lagab','Mangayon','Mapaca','Maparat','New Alegria','Ngan','Osmeña','Panansalan','Poblacion','San','San','Siocon','Tamia'
    ],

    'Laak' => [
        'Aguinaldo','Amor Cruz','Ampawid','Andap','Anitap','Bagong Silang','Banbanon','Belmonte','Binasbas','Bullucan','Cebulida','Concepcion','Datu Ampunan','Datu Davao',	'Doña Josefa',	'El Katipunan','Il Papa',	'Imelda','Inacayan','Kaligutan','Kapatagan','Kidawa','Kilagding','Kiokmay','Laac','Langtud','Longanapan','Mabuhay','Macopa','Malinao','Mangloy','Melale','Naga','New Bethlehem','Panamoren','Sabud','San Antonio','Santa Emilia','Santo Niño','Sisimon',
    ],
    
    'Mabini' => [
        'Cadunan','Pindasan','Cuambog (Poblacion)','Tagnanan (Mampising)','Anitapan','Cabuyuan','Del Pilar','Libodon','Golden Valley (Maraut)','Pangibiran','San Antonio',
    ],

    'Maco' => [
        'Anibongan','Anislagan','Buanan','Bucana','Calabcab','Concepcion','Dumlan','Elizalde (Somil)','Pangi (Gaudencio Antonio)','Gubatan','Hijo','Kinuban','Langgam','Lapu-lapu','Libay-libay','Limbo','Lumatab','Magangit','Malamodao','Manipongol','Mapaang','Masara','New Asturias','Panibasan','Panoraon','Poblacion','San Juan','San Roque','Sangab','Taglawig','Mainit','New Barili','New Leyte','New Visayas','Panangan','Tagbaros','Teresa','Ubalaz','Unangian','Uracia','Vacolan','Vancezo',
    ],

    'Maragusan' => [
        'Bagong Silang','Mapawa','Maragusan (Poblacion)','New Albay','Tupaz','Bahi','Cambagang','Coronobé','Katipunan','Lahi','Langgawisan','Mabugnao','Magcagong','Mahayahay','Mauswagon','New Katipunan','New Manay','New Panay','Paloc','Pamintaran','Parasanon','Talian','Tandik','Tigbao',
    ],

    'Mawab' => [
        'Andili','Bawani','Concepcion','Malinawon','Nueva Visayas','Nuevo Iloco','Poblacion','Salvacion','Saosao','Sawangan','Tuboran',

    ],

    'Monkayo' => [
        'Awao', 'Babag', 'Banlag', 'Baylo', 'Casoon', 'Inambatan', 'Haguimitan', 'Macopa', 'Mamunga', 'Mount Diwata (Mt. Diwalwal)', 'Naboc', 'Olaycon', 'Pasian (Santa Filomena)', 'Poblacion', 'Rizal', 'Salvacion', 'San Isidro', 'San Jose', 'Tubo-tubo (New Del Monte)', 'Upper Ulip', 'Union',
    ],

    'Montevista' => [
        'Banagbanag', 'Banglasan', 'Bankerohan Norte', 'Bankerohan Sur', 'Camansi', 'Camantangan', 'Concepcion', 'Dauman', 'Canidkid', 'Lebanon', 'Linoan', 'Mayaon', 'New Calape', 'New Dalaguete', 'New Cebulan (Sambayon)', 'New Visayas', 'Prosperidad', 'San Jose (Poblacion)', 'San Vicente', 'Tapia',
    ],

    'Nabunturan' => [
        'Anislagan','Antequera','Basak','Bayabas','Bukal','Cabacungan','Cabidianan','Katipunan','Libasan','Linda','Magading','Magsaysay','Mainit','Manat','Matilo','Mipangi','New Dauis','New Sibonga','Ogao','Pangutosan','Poblacion', 'San Isidro','San Roque','San Vicente','Santa Maria','Santo Niño (Kao)','Sasa','Tagnocon',
    ],

    'New Bataan' => [
        'Andap', 'Bantacan', 'Batinao', 'Cabinuangan (Poblacion)', 'Camanlangan', 'Cogonon', 'Fatima', 'Kahayag', 'Katipunan', 'Magangit', 'Magsaysay', 'Manurigao', 'Pagsabangan', 'Panag', 'San Roque', 'Tandawan',
    ],

    'Pantukan' => [
        'Bongabong', 'Bongbong', 'P. Fuentes', 'Kingking (Poblacion)', 'Magnaga', 'Matiao', 'Napnapan', 'Tagdangua', 'Tambongon', 'Tibagon', 'Las Arenas', 'Araibo', 'Tagugpo',
    ]


];

// This part automatically transforms the list for your Select component
$flattened = [];
foreach ($data as $municipality => $barangays) {
    foreach ($barangays as $name) {
        $flattened[] = [
            'name' => $name, 
            'municipality' => $municipality
        ];
    }
}


return ['barangays' => $flattened];



