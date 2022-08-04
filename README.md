# Laravel Object Oriented Design With Actions
Object oriented structural design for creating objects coupled together with actions  

## Highlights
- This package considers every Model as an Object and creates the Objects and Actions based on that concept.
- This package introduces the concept of Object Actions which promotes segregation of any Object (\App\Object\{object}) related business logic into dedicated Action (\App\Objects\{object}\Actions\{action}) classes. 
- An action can use argument count as a means to differentiate between action types or sub actions. 
- Every Action extends an abstract class (Action) and is required to implement a public take method on it. 

## How to use Actions
- There are two ways to initiate/use an action
- An action can be called directly by instantiating an Action class and calling it's take method
``` 
    $action     = new ActionName;
    $response   = $action->take($args);
```
- Or using the base class, an action can be initiated by following the syntax
```
    $response = Action::ObjectNameActionName($args);
```
This is can be percived both as syntactical sugar and a reductor in your number of lines of code as it saves you from importing all the Action classes. You only import the base Action class.

## Namespace
All your Objects by default go in \App\Objects and Actions go in the Actions directory of a particular object. The same can be configured in the owa config file
- app
    - Objects
        - ExampleObject
            - Actions
                - ExampleAction.php 

## Nomenclature/Conventions
For the purpose of consistency a specific naming convention is followed primarily with routing(optional) and action names.
- Every action is named in the format name of the action followed by the suffix __Action__. 
    - Example: For a search action, the action class will be named *SearchAction.php*.
    
- The route defination convention is optional but can be followed in accordance with an object and route specific action, following the course __base_url/object/action__.
    - Example: If we are searching for a store, we will probably have an action in the Store object named SearchAction. The route for the same according to above convention will be *base_url/store/search*    
    
- If a route is an active record route or a SingularAction*, it can be followed by the same object/action convention for active record specific sub actions
    - Example if we have a store and we want to search an item within that store and we have a SearchAction in Item Object, the route will be defined as *base_url/store/{store_reference}/item/search*

- The route conventions are for the sake of easily discovering the source files and is totally optional.
 
[*] SingularActions are those actions where there is an identifier passed in the url instead of an action name. Eg. For a specific store we might have an endpoint like __base_url/store/{store_reference}__

