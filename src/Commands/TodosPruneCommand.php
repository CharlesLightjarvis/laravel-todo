<?php

namespace CharlesLightjarvis\Todo\Commands;

use Illuminate\Console\Command;

class TodosPruneCommand extends Command
{
    public $signature = 'todos:prune {--days=30 : Delete completed todos older than N days}';

    public $description = 'Delete completed todos older than the given number of days';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
