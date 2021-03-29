<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Bref\Event\Handler;
use Bref\Context\Context;
use AsyncAws\Lambda\LambdaClient;

class Application implements Handler
{
    public function handle($event, Context $context)
    {
        // Save 5 layer versions
        $save = $event['save'] ?? 5;

        // Get regions or default to "current"
        $regions = explode(' ', $event['regions'] ?? '');
        if (empty($regions)) {
            $regions[] = '';
        }

        foreach ($regions as $region) {
            if ($region === '') {
                $lambda = new LambdaClient();
            } else {
                $lambda = new LambdaClient(['region'=>$region]);
            }

            $this->removeOldVersions($lambda, $save);
        }
    }

    private function removeOldVersions(LambdaClient $lambda, int $save)
    {
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
                echo sprintf('Removing function version: '.$arn).PHP_EOL;
                $lambda->deleteFunction(['FunctionName' => $arn]);
            }
        }
    }
}

return new Application();