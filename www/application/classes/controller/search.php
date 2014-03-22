<?
class Controller_Search extends Template {
    function action_json_city() {
		$search = Arr::get($_REQUEST,'search');
		$this->auto_render = false;
		$this->response->headers('Content-Type','application/json');

		if (!empty($search) && mb_strlen($search) > 2)
		{
			$items = ORM::factory('city');
            $items->where('regions_id','in',DB::expr('(' . implode(',',Arr::Make1Array($this->location->country->regions->find_all(),'id')) . ')'));
			$items->where('name','LIKE','%'.$search.'%')->limit(30);
                        print_r($items->find_all());
			$items = array('error'=>'no','items' => Arr::Make2Array($items->find_all(),'id',array(array('fullname',true))));

			$this->response->body(json_encode($items));
		} else {
			$this->response->body('{"error":"no","items":[]}');
		}
	}
    
    function action_json_city_areas() {
		$city_id = Arr::get($_REQUEST,'city_id');
		$this->auto_render = false;
		$this->response->headers('Content-Type','application/json');
		if (!empty($city_id)) {
			$items = ORM::factory('cities_area');
            $items = $items->where('cities_id','=',$city_id)->find_all();
            $_items = array();
            foreach($items as $item) {
                array_push($_items,array(
                    'id' => $item->id,
                    'name' => $item->name,
                ));
            }
			$this->response->body(json_encode($_items));
		} else {
			$this->response->body('{"error":"no","items":[]}');
		}
	}
}