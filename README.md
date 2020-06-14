# orderCY

getRestaurants returns an array of RestaurantModel objects. Each model represents a restaurant with its attributes being name, city, openhour,venueID. each restaurant can be accessed normaly like an array:

let restauratns = getRestaurants()
restaurantNo1 = restaurants[1]

and each restaurant's attribute like:

restaurantNo1Name = restaurantNo1.name

same ideology follows for the getItemsOfRestaurant but the venueID must be provided as a parameter as a string

structure of RestaurantModel and ItemModel:

class RestaurantModel {
    var name: String?
    var city: String?
    var openhour: String?
    var venueID: String?    
}
 
class ItemModel{
    var name :String?
    var description :String?
    var type :String?
    var price :String?
    var itemID :String?
    var venueID :String?
    var visible :String?
}



everything written here is written with case sensitivity.



