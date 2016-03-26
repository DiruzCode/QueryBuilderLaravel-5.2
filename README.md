# QueryBuilderControlle
This is a controller for avoid trouble common the DRY (don't repeat yourself) when you need search information in you system.

You can use this controller for Querys Dynamic in the same controller, for example Wheres and Join Dynamic.

Example in AngularJs

$Api.query({
	model : 'myModel',
	where : "title like %"+$scope.title+"%"
}).then(function(result){
	$scope.model = JSON.parse(JSON.stringify(result.data));
});	


OR

var getTypeUser = function()
{
    var deferred = $q.defer();

    $Api.query({
        model: 'user',
        where: 'user.type_user_id <> 4,user.type_user_id <> 1',
        join : 'type_user user.type_user_id = type_user.type_user_id'
        select:'user.type_user_id,type_user.title'
    })
    .success(function(result)
    {
        $scope.user.type_user = result;
        deferred.resolve(result);
    })
    .error(deferred.reject);


    return deferred.promise;
    
};