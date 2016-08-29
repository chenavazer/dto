<?php
class GetValueMutatorTest extends DtoTest\TestCase
{
    public function testDefaultValueReturned()
    {
        $value = $this->callProtectedMethod(new \Dto\Dto(), 'getValueMutator', ['']);
        $this->assertEquals('mutateTypeUnknown', $value);
    }
    
    /**
     * @expectedException \Dto\Exceptions\InvalidMutatorException
     */
    public function testExceptionThrownForUndefinedTypeMutator()
    {
        $meta = [
            '.x' => [
                'type' => 'does_not_exist'
            ],
            '.' => [
                'type' => 'hash',
                'values' => [
                    'type' => 'does_not_exist'
                ]
            ]
        ];
        $dto = new \Dto\Dto([],[],$meta);
        $this->callProtectedMethod($dto, 'getValueMutator', ['x']);
    }
    
    public function testFieldLevelMutatorReturnedWhenMethodExists()
    {
        $meta = [
            '.x' => [
                'type' => 'scalar',
                'mutator' => 'mutateMyX'
            ]
        ];
        $dto = new TestGetValueMutatorDto([],[],$meta);
        $value = $this->callProtectedMethod($dto, 'getValueMutator', ['x']);
        $this->assertEquals('mutateMyX', $value);
    }
    
    /**
     * @expectedException \Dto\Exceptions\InvalidMutatorException
     */
    public function testFieldLevelMutatorFailsWhenMethodDoesNotExist()
    {
        $meta = [
            '.x' => [
                'type' => 'scalar',
                'mutator' => 'does_not_exist'
            ]
        ];
        $dto = new TestGetValueMutatorDto([],[],$meta);
        $this->callProtectedMethod($dto, 'getValueMutator', ['x']);
    }
    
    
    public function testTypeLevelMutatorReturned()
    {
        $meta = [
            '.x' => [
                'type' => 'boolean'
            ]
        ];
        $dto = new \Dto\Dto([],[],$meta);
        $value = $this->callProtectedMethod($dto, 'getValueMutator', ['x']);
        $this->assertEquals('mutateTypeBoolean', $value);
    }
    
    public function testValueLevelMutator()
    {
        $meta = [
            '.x' => [
                'type' => 'array',
                'values' => [
                    'type' => 'boolean'
                ]
            ],
            '.' => [
                'type' => 'hash',
                'values' => [
                    'type' => 'integer'
                ]
            ]
        ];
        $dto = new \Dto\Dto([],[],$meta);
        $reflection = new ReflectionClass(get_class($dto));
        $method = $reflection->getMethod('getValueMutator');
        $method->setAccessible(true);
    
        $value = $method->invokeArgs($dto, ['x']);
        $this->assertEquals('mutateTypeInteger', $value);
    }
    
    /**
     *
     */
    public function testDeferToParentCustomMutator()
    {
        $meta = [
            '.x' => [
                // empty -- no data defined for this node
            ],
            '.' => [
                'type' => 'hash',
                'values' => [
                    'type' => 'integer',
                    'mutator' => 'mutateMyX'
                ]
            ]
        ];
        $dto = new TestGetValueMutatorDto([],[],$meta);
        $value = $this->callProtectedMethod($dto, 'getValueMutator', ['x']);
        $this->assertEquals('mutateMyX', $value);
    }
}

class TestGetValueMutatorDto extends \Dto\Dto {
    
    function mutateMyX($value) {
        return $value;
    }
}