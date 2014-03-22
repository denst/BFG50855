<?

class Controller_Functions extends Controller {

    function action_movedistricts() {

        $cities = ORM::factory('city')->find_all();

        foreach($cities as $city) {

            $district = ORM::factory('district',array( 'name' => $city->district ));

            if ($district->loaded()) {

                $city->districts_id = $district->id;
                $city->save();

            } else {

                $district->name = $city->district;
                $district->save();

                $city->districts_id = $district->id;
                $city->save();

            }

        }

        die('Операция успешно завершена');

    }


}

?>
