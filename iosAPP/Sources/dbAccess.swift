import Foundation
import FoundationNetworking



 func getRestaurants() -> Array<RestaurantModel>{
     //retrieve data from site
        let url = URL(string: "http://localhost/orderCY/getRestaurants.php")!
        let data = try!(Data(contentsOf: url))
        
        var jsonResult = NSArray()
        var Restaurants =  Array<RestaurantModel>()
        
        //decode data to json string
        do{
            jsonResult = try JSONSerialization.jsonObject(with: data, options:JSONSerialization.ReadingOptions.allowFragments) as! NSArray
            
        } catch let error as NSError {
            print(error)
            
        }
        
        var jsonElement = NSDictionary()
        
        for i in 0 ..< jsonResult.count
        {
            
            jsonElement = jsonResult[i] as! NSDictionary
            
            let restaurant = RestaurantModel()
            
            //the following insures none of the JsonElement values are nil through optional binding
            if let name = jsonElement["Name"] as? String,
                let city = jsonElement["City"] as? String,
                let openhour = jsonElement["Openhour"] as? String,
                let venueID = jsonElement["VenueID"] as? String
            {
                
                restaurant.name = name
                restaurant.city = city
                restaurant.openhour = openhour
                restaurant.venueID = venueID
                
            }
            Restaurants.append(restaurant)
            
        }

        return Restaurants
       
    }


 func getItemsOfRestaurant(venueID:String) -> Array<ItemModel>{
        let myUrl = URL(string: "http://localhost/orderCY/getItems.php?venueID=\(venueID)")!;
        let data = try!(Data(contentsOf: myUrl))
        var jsonResult = NSArray()
        var items =  Array<ItemModel>()
        
        do{
            jsonResult = try JSONSerialization.jsonObject(with: data, options:JSONSerialization.ReadingOptions.allowFragments) as! NSArray
            
        } catch let error as NSError {
            print(error)
            
        }
        
        var jsonElement = NSDictionary()
        
        for i in 0 ..< jsonResult.count
        {
            
            jsonElement = jsonResult[i] as! NSDictionary
            
            let item = ItemModel()
            
            //the following insures none of the JsonElement values are nil through optional binding
            if let name = jsonElement["Name"] as? String,
                let description = jsonElement["Description"] as? String,
                let type = jsonElement["Type"] as? String,
                let price = jsonElement["Price"] as? String,
                let itemID = jsonElement["ItemID"] as? String,
                let venueID = jsonElement["VenueID"] as? String,
                let visible = jsonElement["Visible"] as? String
            {
                
                item.name = name
                item.description = description
                item.type = type
                item.price = price
                item.itemID = itemID
                item.venueID = venueID
                item.visible = visible
                
            }
            items.append(item)
            
        }

        return items
       
    }



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

class RestaurantItemsModel{
    var restaurant:RestaurantModel?
    var items: Array<ItemModel>?
}


