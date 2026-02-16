<?php

namespace App\Services;

use App\Models\Contest;
use Carbon\CarbonImmutable;
use RuntimeException;

class ContestService
{
    /**
     * @return \Illuminate\Support\Collection<int, Contest>
     */
    public function all()
    {
        return Contest::query()
            ->orderByDesc('created_at')
            ->get();
    }

    public function create(array $data): Contest
    {
        $this->assertDeadline($data);

        return Contest::query()->create($data);
    }

    public function update(Contest $contest, array $data): Contest
    {
        $this->assertDeadline($data);

        $contest->fill($data);
        $contest->save();

        return $contest;
    }

    public function delete(Contest $contest): void
    {
        $contest->delete();
    }

    protected function assertDeadline(array $data): void
    {
        if (isset($data['is_active'], $data['deadline_at']) && $data['is_active']) {
            $deadline = CarbonImmutable::parse($data['deadline_at']);

            if ($deadline->isPast()) {
                throw new RuntimeException('Дедлайн активного конкурса не может быть в прошлом.');
            }
        }
    }
}


