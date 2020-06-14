import Foundation
import FoundationNetworking

let url = URL(string: "http://localhost/orderCY/getRestaurants.php")!
let jsonString:String=(try(String(data: (Data(contentsOf: url)), encoding: .utf8)!))

getItemsOfRestaurantInJSON(venueID:"1")

func getItemsOfRestaurantInJSON(venueID:String){
    
    let myUrl = URL(string: "http://localhost/orderCY/getItems.php")!

    var request = URLRequest(url:myUrl)

    request.httpMethod = "POST"// Compose a query string
    
    let postString = "venueID="+venueID;
    
    request.httpBody = postString.data(using: String.Encoding.utf8);
    let task = URLSession.shared.dataTask(with: request) { (data: Data?, response: URLResponse?, error: Error?) in
    do{
            // You can print out response object
    print(try(String(data: Data)))

    }catch{
        print("error")
    }

    }


        


}

 func parseRestaurantJSON(_ data:Data) -> Array<RestaurantModel>{
        
        var jsonResult = NSArray()
        var Restaurants =  Array<RestaurantModel>()
        
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
        //print(Restaurants[0].name!)

        return Restaurants
       
    }


 func parseItemsJSON(_ data:Data) -> Array<ItemModel>{
        
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
        //print(Restaurants[0].name!)

        return items
       
    }

//print(jsonString)


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
    var items: ItemModel?
}



  

