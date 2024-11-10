<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use Firebase\JWT\JWT;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class GenerateKeyToken extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'key:api';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:api {--name=} {--credential=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate JWT access API";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if (is_null($this->option('name'))) {
            $this->error('Enter the owner name');

            return;
        }

        if (is_null($this->option('credential'))) {
            $this->error('Enter the unique credential');

            return;
        }

        $name = $this->option('name');
        $credential = $this->option('credential');

        $this->warn('Generating Key...');

        $jwt = new JWT();

        /*
        * Token data
        */
        $data = [
            "credential" => $this->option('credential'),
            "iss" => url('/api'),
            "iat" => time(),
        ];

        $token = $jwt->encode($data, Config::get('app.key'), 'HS256');

        $apiUser = ApiToken::where('credential', $credential)->first();

        if (!is_null($apiUser)) {
            $this->error('Credential already exists.');

            return;
        }

        ApiToken::create([
            'credential' => $credential,
            'name' => $name,
            'token' => $token,
            'status' => true,
        ]);

        $this->info('Key generated successfully: ' . $token);
    }
}
