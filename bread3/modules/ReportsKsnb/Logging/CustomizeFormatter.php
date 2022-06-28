<?php
 
namespace Modules\ReportsKsnb\Logging;
 
use Monolog\Formatter\LineFormatter;
 
class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                '['.date("Y-m-d H:i:s").'] '.\Session::getId().' %level_name%: %message% ' . PHP_EOL,
                NULL,
                TRUE,
                TRUE
            ));
        }
    }
}