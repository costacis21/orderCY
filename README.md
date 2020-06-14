getRestaurants returns an array of RestaurantModel objects. Each model represents a restaurant with its attributes being name, city, openhour,venueID. each restaurant can be accessed normaly like an array:

let restauratns = getRestaurants()
restaurantNo1 = restaurants[1]

and each restaurant's attribute like:

restaurantNo1Name = restaurantNo1.name

same ideology follows for the getItemsOfRestaurant but the venueID must be provided as a parameter as a string
# orderCY
