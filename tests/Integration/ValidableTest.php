<?php

namespace Padosoft\Laravel\Validable\Test\Integration;

class ValidableTest extends TestCase
{


    /** @test */
    public function testNotSaveInvalid()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModel();
        $model->order = 1;
        $ret = $model->save();
        $this->assertEquals($ret, false);
        $this->assertEquals($model->hasErrors(), true);
        $this->assertEquals(count($model->getErrors()), 1);
        $this->assertDatabaseMissing('test_models', [
            'name' => 'test'
        ]);
    }

    /** @test */
    public function testSaveValid()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModel();
        $model->name = 'test';
        //$model->order = 1;
        $ret = $model->save();
        $this->assertDatabaseHas('test_models', [
            'name' => 'test'
        ]);
        $this->assertEquals($ret, true);
        $this->assertEquals($model->hasErrors(), false);
    }

    /** @test */
    public function testNotSaveInvalidNotMandatory()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModel();
        $model->name = 'test';
        $model->order = 'a';
        $ret = $model->save();
        $this->assertEquals($ret, false);
        $this->assertEquals($model->hasErrors(), true);
        $this->assertEquals(count($model->getErrors()), 1);
        $this->assertDatabaseMissing('test_models', [
            'name' => 'test'
        ]);
    }

    /** @test */
    public function testCanGetRulesAndMessagesOfAModel()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $rules=TestModel::getRules();
        $messages=TestModel::getMessages();
        $this->assertEquals(count($rules), 2);
        $this->assertEquals(count($messages), 1);

    }



}
