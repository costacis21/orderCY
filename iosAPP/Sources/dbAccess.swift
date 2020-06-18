import Foundation
import FoundationNetworking



 func getRestaurants() -> Array<RestaurantModel>{
     //retrieve data from site
        let url = URL(string: "https://ordercy.a2hosted.com/orderCY/getRestaurants.php")!
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
        let myUrl = URL(string: "https://ordercy.a2hosted.com/orderCY/getItems.php?venueID=\(venueID)")!;
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




    func getItemByID(itemID: String, itemArray: Array<ItemModel>)->ItemModel{
        var nullItem : ItemModel = ItemModel() //= ItemModel(itemID:"",type:"", price:"", name:"", venueID:"", visible:"", description: "" )
        for item in itemArray{
            if item.itemID == itemID{
                return item
            }
        }
        return nullItem

    }

    func sumbmitOrder(CustomerOrder:CustomerOrderModel){
        
    let customerorder : String = encodeCustomerOrderModel(customerToEncode: CustomerOrder.customer!, orderToEncode: CustomerOrder.itemOrders!)
    let escapedString = customerorder.addingPercentEncoding(withAllowedCharacters: .urlHostAllowed)

    var urlQueryItem = URLQueryItem(name: "order", value: customerorder)
    var urlComponents = URLComponents()
    urlComponents.scheme = "http"
    urlComponents.host = "localhost"
    urlComponents.path = "/orderCY/insertOrder.php"
    urlComponents.queryItems = [urlQueryItem]
    
    let myUrl = URL(string: urlComponents.url!.absoluteString)
    if let url = myUrl {
    do {
        let contents = try String(contentsOf: url,encoding: .utf8)
        print(contents)
    } catch {
        // contents could not be loaded
        print("contents not loaded \(error)")
    }
    } else {
        // the URL was bad!
        print("url was bad")
    }


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
        var orderItem: orderModel = orderModel()

        for item in orderToEncode{
            orderItem.itemID = item.itemID
            orderItem.comment = item.comment
            orderItem.commentQty = item.commentQty
            orderItem.quantity = item.quantity
            order.append(orderItem)
        }


        let encoder = JSONEncoder()
        //encoder.outputFormatting = .prettyPrinted

        let customerData = try! (encoder.encode(customer))
        let orderData = try! (encoder.encode(order))

        return ((String(data: customerData, encoding: .utf8)!)+(String(data: orderData, encoding: .utf8)!))
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

//Testing
/* var items : Array<ItemModel>! = Array(arrayLiteral: ItemModel())

items = getItemsOfRestaurant(venueID:"1");

var order : Array<OrderModel>! = Array(arrayLiteral: OrderModel())

var orderItem: OrderModel = OrderModel()
var orderItem1: OrderModel = OrderModel()


orderItem.itemID = items[0].itemID
orderItem.comment = "dfsd"
orderItem.quantity = "34"
orderItem.commentQty = "54"
order.append(orderItem)

orderItem1.itemID = items[1].itemID
orderItem1.comment = "bdsdfsd"
orderItem1.quantity = "32"
orderItem1.commentQty = "124"
order.append(orderItem1)




var customer : CustomerModel! = CustomerModel()

customer.tableNo = "10"
customer.telNo = "99938434"
customer.venueID = "1"


let customerOrder = CustomerOrderModel(Customer: customer, Orders: order) 


sumbmitOrder(CustomerOrder: customerOrder)
 print(getItemByID(itemID:"2", itemArray:items).name)
*/



