<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateMemberCodesSeeder extends Seeder
{
    public function run(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users */
        $users = User::whereNull('member_code')->get();

        if ($users->isEmpty()) {
            echo "Semua user sudah memiliki member code.\n";
            return;
        }

        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $user->member_code = User::generateMemberCode($user->member_level);
            $user->save();

            echo "Generated code for {$user->email}: {$user->member_code}\n";
        }

        echo "\nTotal users updated: " . $users->count() . "\n";
    }
}
