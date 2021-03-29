<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$handler = function ($event) {
    // Save 5 layer versions
    $save = $event['save'] ?? 5;
    die(json_encode($event));
    $lambda = new \AsyncAws\Lambda\LambdaClient();
    /** @var  $function */
    foreach ($lambda->listFunctions()->getFunctions() as $function) {
        $versions = $lambda->listVersionsByFunction(['FunctionName' => $function->getFunctionArn()]);
        $arns = [];

        // Collect versions
        foreach ($versions->getVersions() as $version) {
            // Dont get the latest version
            if ($version->getVersion() !== '$LATEST') {
                $arns[$version->getFunctionArn()] = $version->getVersion();
            }
        }

        asort($arns);
        $arns = array_slice(array_keys($arns), 0, -1 * $save);

        foreach ($arns as $arn) {
            $lambda->deleteFunction(['FunctionName' => $arn]);
        }
    }
};

return $handler;