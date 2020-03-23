<?php

class ApiCest 
{    
    public function tryApi(ApiTester $I)
    {
        $I->sendGET('/v1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'version' => PHP_VERSION
        ]);
    }
}
