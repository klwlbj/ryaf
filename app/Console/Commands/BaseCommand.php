<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    use ConfirmableTrait;

    public const CHECK_TIME = 30 * 60;

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->getDefinition()->addOption(new InputOption(
            name:'force',
            description:'强制在生产环境使用'
        ));
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->confirmToProceed()) {
            return 0;
        }

        $result = parent::execute($input, $output);

        // 增加脚本成功执行记录，方便排查问题
        if (is_int($result)) {
            $command = $input->getArgument('command');
            Log::info($command . ' is done');
        }
        return $result;
    }

    /**
    * 设置小于等于当前时间的查询条件.
    *
    * @param Builder|Model $query
    * @param array|string $columns
    * @param int|\DateTime|null $time
    * @return Builder|Model
    */
    protected function setTimeCondition($query, $columns, $time = null, $isTimeStamp = true)
    {
        $endTime   = new Carbon($time);
        $startTime = $endTime->clone()->subSeconds(static::CHECK_TIME);

        if ($isTimeStamp) {
            $endTime   = $endTime->unix();
            $startTime = $startTime->unix();
        }

        foreach (Arr::wrap($columns) as $column) {
            if (config('app.env') === 'local') {
                $query->where($column, '<=', $endTime);
            } else {
                $query->whereBetween($column, [$startTime, $endTime]);
            }
        }

        return $query;
    }
}
