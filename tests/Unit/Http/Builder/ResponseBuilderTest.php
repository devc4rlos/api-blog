<?php

namespace Tests\Unit\Http\Builder;

use App\Http\Builder\ResponseBuilder;
use Illuminate\Http\Resources\Json\JsonResource;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResponseBuilderTest extends TestCase
{
    public static function provideSetterTests(): array
    {
        return [
            'message'  => ['setMessage', 'getMessage', 'Test message'],
            'code'     => ['setCode', 'getCode', 404],
            'result'   => ['setResult', 'getResult', ['data' => 'value']],
            'warnings' => ['setListWarning', 'getWarnings', ['warning' => 'a warning']],
            'metadata' => ['setListMetadata', 'getMetadata', ['meta' => 'info']],
        ];
    }

    #[DataProvider('provideSetterTests')]
    public function test_should_set_and_get_properties_correctly(string $setterMethod, string $getterMethod, mixed $value)
    {
        $builder = new ResponseBuilder();

        $builder->{$setterMethod}($value);

        $this->assertSame($value, $builder->{$getterMethod}());
    }

    public function test_should_set_warning()
    {
        $attribute = 'warning_attribute';
        $value = 'warning_value';

        $builder = new ResponseBuilder();
        $builder->setWarning($attribute, $value);

        $this->assertSame([$attribute => $value], $builder->getWarnings());
    }

    public function test_should_set_metadata()
    {
        $attribute = 'metadata_attribute';
        $value = 'metadata_value';

        $builder = new ResponseBuilder();
        $builder->setMetadata($attribute, $value);

        $this->assertSame([$attribute => $value], $builder->getMetadata());
    }

    public function test_should_set_result_resource()
    {
        $data = [
            'status' => 'success',
        ];
        $mock = Mockery::mock(JsonResource::class);
        $mock->shouldReceive('toArray')->andReturn($data);

        $builder = new ResponseBuilder();
        $builder->setResultResource($mock);

        Mockery::close();
        $this->assertSame($data, $builder->getResult());
    }

    public function test_should_set_the_response_with_the_date_attribute_when_the_code_is_positive()
    {
        $result = ['status' => 'success'];

        $builder = new ResponseBuilder();
        $builder->setResult($result);

        $dataResponse = $builder->getDataResponse();

        $this->assertArrayHasKey('data', $dataResponse);
        $this->assertEquals($result, $dataResponse['data']);
    }

    public function test_should_set_the_response_with_the_errors_attribute_when_the_code_is_negative()
    {
        $result = ['status' => 'error'];
        $code = 500;

        $builder = new ResponseBuilder();
        $builder->setResult($result)->setCode($code);

        $dataResponse = $builder->getDataResponse();

        $this->assertArrayHasKey('errors', $dataResponse);
        $this->assertEquals($result, $dataResponse['errors']);
    }

    public function test_should_return_the_default_value_message()
    {
        $builder = new ResponseBuilder();
        $this->assertEmpty($builder->getMessage());
    }

    public function test_should_return_the_default_value_code()
    {
        $builder = new ResponseBuilder();
        $this->assertSame(200, $builder->getCode());
    }

    public function test_should_return_the_default_value_result()
    {
        $builder = new ResponseBuilder();
        $this->assertNull($builder->getResult());
    }

    public function test_should_return_the_default_value_metadata()
    {
        $builder = new ResponseBuilder();
        $this->assertIsArray($builder->getMetadata());
        $this->assertEmpty($builder->getMetadata());
    }

    public function test_should_return_the_default_value_warnings()
    {
        $builder = new ResponseBuilder();
        $this->assertIsArray($builder->getWarnings());
        $this->assertEmpty($builder->getWarnings());
    }

    public function test_should_return_the_default_value_data_response()
    {
        $builder = new ResponseBuilder();
        $this->assertEquals(['message' => ''], $builder->getDataResponse());
    }
}
