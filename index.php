<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;

$client = new Client([
    'base_uri' => 'https://api.bulutfon.com/v2/',
    'timeout'  => 2.0,
    'query'   => ['apikey' => 'api-anahtarinizi-buraya-yazin', 'limit' => 5, 'page' => $page]
]);

$request = $client->request('GET', 'cdr/list');
$response = $request->getBody()->getContents();
$cdrs = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CDR Listesi</title>

        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="text-gray-900 bg-gray-200">
        <div class="w-1/2 mx-auto">
            <div class="px-3 py-4 flex justify-center">
                <table class="w-full text-md bg-white shadow-md rounded mb-4">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left p-3 px-5">Arayan</th>
                            <th class="text-left p-3 px-5">Aranan</th>
                            <th class="text-right p-3 px-5">Ses Kaydı</th>
                        </tr>
                        <?php foreach ($cdrs['data'] as $cdr): ?>
                        <tr class="border-b hover:bg-orange-100 bg-gray-100">
                            <td class="p-3 px-5">
                                <?php echo $cdr['caller']; ?>
                            </td>
                            <td class="p-3 px-5">
                                <?php echo $cdr['callee']; ?>
                            </td>
                            <td class="p-3 px-5 ">
                                <?php if ($cdr['call_record'] !== 'NO'): ?>
                                <div class="w-full flex justify-end">
                                    <audio controls>
                                        <source src="<?php echo $cdr['call_record']; ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio> 
                                </div>
                                <?php else: ?>
                                    Arama kaydı yok
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-center">Toplam <?php echo $cdrs['pagination']['total_pages']; ?> sayfadan <?php echo $page; ?>. sayfayı görüntülüyorsunuz. </p>
            <div class="flex justify-center">
                <ul class="flex pl-0 list-none rounded my-2">
                    <li class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-blue-700 border-r-0 ml-0 rounded-l hover:bg-gray-200">
                        <?php if($page <=1): ?>
                        <a disabled class="page-link disabled:opacity-75">Geri</a>
                        <?php else: ?>
                        <a class="page-link" href="index.php?page=<?php echo $page - 1; ?>">Geri</a>
                        <?php endif; ?>
                    </li>
                    <li class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-blue-700 rounded-r hover:bg-gray-200">
                        <?php if($cdrs['pagination']['total_pages'] <= $page): ?>
                        <a disabled class="page-link disabled:opacity-75">İleri</a>
                        <?php else: ?>
                        <a class="page-link" href="index.php?page=<?php echo $page + 1; ?>">İleri</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>