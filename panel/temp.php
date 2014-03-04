<?php

$content = file_get_contents('https://partner-int-api.groupon.com/deals.json?country_code=AU&tsToken=AU_AFF_0_200615_213399_0&CID=AU_AFF_5600_225_5383_1&nlp&utm_source=GPN&utm_medium=afl&utm_campaign=200615&division_id=sydney-premium');

$content = json_decode($content);

print_r(array_keys($content->deals));