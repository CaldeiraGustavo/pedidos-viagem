<?php

namespace Tests\Feature\Commands;

use App\Enums\TestsEnum;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\ApiToken;

class GenerateKeyTokenTest extends TestCase
{
    use DatabaseTransactions;

    public function testShouldReturnMissingName()
    {
        $this->artisan('key:api')->expectsOutput('Enter the owner name');
    }

    public function testShouldReturnMissingCredential()
    {
        $this->artisan('key:api --name="teste@email.com"')
            ->expectsOutput('Enter the unique credential');
    }

    public function testShouldReturnCredentialAlreadyExists()
    {
        ApiToken::create([
            'credential' => 'teste-gustavo',
            'name' => 'gustavo',
            'token' => 'tokentest',
            'status' => true,
        ]);
        $this->artisan('key:api --name="gustavoC" --credential="teste-gustavo"')
             ->expectsOutput('Generating Key...')
             ->expectsOutput('Credential already exists.');
    }

    public function testShouldReturnSuccess()
    {
        $this->artisan('key:api --name="Gustavoc" --credential="teste-backend"')
             ->expectsOutput('Generating Key...')
             ->expectsOutputToContain('Key generated successfully: ');
    }
}
