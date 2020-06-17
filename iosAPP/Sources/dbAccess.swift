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

    func sumbmitOrder(CustomerOrder:CustomerOrderModel){
        //var urlData:String

        // for item in CustomerOrder.itemOrders {
        //     urlData+=""

        // }

    // let data = NSJSONSerialization.dataWithJSONObject(CustomerOrder.itemOrders, options: nil, error: nil)
    // let string = NSString(data: data!, encoding: NSUTF8StringEncoding)
    print(encodeCustomerOrderModel(customerToEncode: CustomerOrder.customer!, orderToEncode: CustomerOrder.itemOrders!))



    }

    func encodeCustomerOrderModel(customerToEncode: CustomerModel, orderToEncode: Array<OrderModel>) -> String{
    
        struct customerModel: Codable {
            var tableNo: String?
            var telNo: String?
            var venueID: String?
        }

        struct orderModel: Codable{
            var itemID: String?
            var comment: String?
            var commentQty: String?
            var quantity: String?
        }

        struct customerordermodel: Codable{
            var customer: customerModel
            var itemOrders: Array<orderModel>
        }

        var customer: customerModel! = customerModel()

        customer.tableNo = customerToEncode.tableNo
        customer.telNo = customerToEncode.telNo
        customer.venueID = customerToEncode.venueID
        
        var order : Array<orderModel>! = Array(arrayLiteral: orderModel())
        var i : Int
        i = 0
        for item in orderToEncode{
            order[i].itemID = orderToEncode[i].itemID
            order[i].comment = orderToEncode[i].comment
            order[i].commentQty = orderToEncode[i].commentQty
            order[i].quantity = orderToEncode[i].quantity
            i+=1
        }


        let encoder = JSONEncoder()
        encoder.outputFormatting = .prettyPrinted

        let data = try! (encoder.encode(customer))
        return (String(data: data, encoding: .utf8)!)
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

class CustomerModel{
    var tableNo: String?
    var telNo: String?
    var venueID: String?
}

class OrderModel: Codable{
    var itemID: String?
    var comment: String?
    var commentQty: String?
    var quantity: String?

}

class CustomerOrderModel{
        var customer: CustomerModel?
        var itemOrders: Array<OrderModel>?

        init(Customer: CustomerModel, Orders: Array<OrderModel>){
            customer=Customer
            itemOrders=Orders
        }
}

class RestaurantItemsModel{
    var restaurant:RestaurantModel?
    var items: Array<ItemModel>?
}


var items : Array<ItemModel>! = Array(arrayLiteral: ItemModel())

items = getItemsOfRestaurant(venueID:"1");

var order : Array<OrderModel> = Array(arrayLiteral: OrderModel())

order[0].itemID = items[0].itemID
order[0].comment = "bla"
order[0].quantity = "bli"
order[0].commentQty = "blo"

var customer : CustomerModel! = CustomerModel()

customer.tableNo = "10"
customer.telNo = "99938434"
customer.venueID = "1"

let customerOrder = CustomerOrderModel(Customer: customer, Orders: order) 
sumbmitOrder(CustomerOrder: customerOrder)


let restaurants: Array<RestaurantModel> = getRestaurants()


