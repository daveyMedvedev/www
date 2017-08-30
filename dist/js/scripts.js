 // define angular module/app
 var crudApp = angular.module('crudApp', []);


 

 crudApp.controller("crudController", function($scope,$http) {   
   $scope.formData = {};
   $scope.searchForm;
   $scope.dbCoaches = [];
   $scope.tempRoles = {};

   $scope.show = true;
  

    $scope.login = function() {
      $http({
        method: 'POST',
        url: '/dist/php/process.php',
        data: $.param($scope.formData), // pass in data as strings
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        } // set the headers so angular passing info as form data (not request payload)
      })
        .success(function(data,response) {
        console.log($scope.formData, "successful?: " + data.success);
        console.log(data);
        
        if (!data.success) {
  
          // if not successful, bind errors to error variables
          $scope.errorLogin = "Error opening Database connection.";

          
  
  
        } else {
          // if successful, bind success message to message

          
  
          window.location = "index.html";
          sessionStorage.setItem("accountName", $scope.formData.username); 
  
  
        }
      });
  
    };
   



   
   $scope.get_coaches = function() {
    $scope.deleteMSG ="";
     $http.get("/dist/php/getData.php?action=getcoach").success(function(response) {
       $scope.dbCoaches = response;
       $scope.searchError = "";
       $scope.addMSG = "";
       console.log($scope.dbCoaches);
 
     });
 
 
   }

  
   $scope.get_users = function() {
    $scope.deleteMSG ="";
    $scope.addMSG = "";
    $http.get("/dist/php/getData.php?action=getuser").success(function(response) {
      $scope.dbUsers = response;
      $scope.searchUserError = "";
     
      console.log($scope.dbUsers);

    });


  }

  $scope.get_encounters = function(x) {
    

    $scope.encounter = {
      "location" : x.location,
      "notes" : x.notes,
      "date" : x.date,
    };



    $http({
			method: 'POST',
			url: '/dist/php/getData.php?action=getencounter',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			transformRequest: function(obj) {
				var str = [];
				for (var p in obj)
				str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
				return str.join("&");
			},
			data: {
				mid: x
			}
		}).success(function(response,data) {
      $scope.encounter = response;
     
			if (response == 'false1') {
        $scope.show = false;
				
			} else  {       
        $scope.show = true;
        console.log(response);
			}
	
		});
 
 
   }

  $scope.search_coach = function() {
		
		if (!$scope.searchForm == "" ) {
			console.log($scope.searchForm.search);
			$http({
			method: 'POST',
			url: '/dist/php/getData.php?action=searchcoach',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			transformRequest: function(obj) {
				var str = [];
				for (var p in obj)
				str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
				return str.join("&");
			},
			data: {
				search: $scope.searchForm.search
			}
		}).success(function(response,data) {
			console.log(response);
			if (!response) {
				$scope.searchError = "No results were found for Coach: " + $scope.searchForm.search;
				$scope.roleSearchError = "";
				$scope.searchForm = null;
				$scope.dbCoaches = null;
				
			} else  {
			$scope.dbCoaches = response;
			$scope.searchForm = null;
			$scope.searchError = "";

			}
	
		});


	
	}
	
  }
  
  $scope.search_user = function() {
		
		if (!$scope.searchForm == "" ) {
			console.log($scope.searchForm.search);
			$http({
			method: 'POST',
			url: '/dist/php/getData.php?action=searchuser',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			transformRequest: function(obj) {
				var str = [];
				for (var p in obj)
				str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
				return str.join("&");
			},
			data: {
				search: $scope.searchForm.search
			}
		}).success(function(response,data) {
			console.log(response);
			if (!response) {
				$scope.searchUserError = "No results were found for User: " + $scope.searchForm.search;
				$scope.searchForm = null;
				$scope.dbUsers = null;
				
			} else  {
			$scope.dbUsers = response;
			$scope.searchForm = null;
			$scope.searchUserError = "";

			}
	
		});


	
	}
	
  }
  

 
  $scope.createProfile = function(x) {
    console.log(x.mid);

    $scope.get_encounters(x.mid);


    $scope.profile = {
        "first" : x.first,
        "middle" : x.mname,
        "last" : x.last,
        "memid" : x.mid,
        "dob" : x.dob,
        "addressline1" : x.addressline1,
        "addressline2" : x.addressline2,
        "city" : x.city,
        "state" : x.state,
        "zip" : x.zip,
        "phone" : x.phone,
        "email" : x.email,
        "workplace":x.workplace,
        "income":x.income,
        "vfood":x.vfood,
        "vbook":x.vbook,
        "visitpref":x.visitpref,
        "cpref":x.cpref,
        "education":x.education,
        "sex":x.sex

    };

    
    console.log($scope.encounter);
    console.log(x);
    
  }
  $scope.delete_coach = function(x) {

  
      $http({
        method: 'POST',
        url: '/dist/php/getData.php?action=deletecoach',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        transformRequest: function(obj) {
          var str = [];
          for (var p in obj)
          str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
          return str.join("&");
        },
        data: {
          id: x.id
        }
      }).success(function(response,data) {
        console.log(response,data);
        $("#coachTable tr").remove(); 
        $scope.get_coaches();
        $scope.deleteMSG = "Your deletion was successful.";
      });
  
    }

    
  $scope.delete_user = function(x) {
    
      
          $http({
            method: 'POST',
            url: '/dist/php/getData.php?action=deleteuser',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            transformRequest: function(obj) {
              var str = [];
              for (var p in obj)
              str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
              return str.join("&");
            },
            data: {
              mid: x.mid
            }
          }).success(function(response,data) {
            console.log(response,data);
            $("#userTable tr").remove(); 
            $scope.get_users();
            $scope.deleteMSG = "Your deletion was successful.";
          });
      
        }

   $scope.add_users = function() {
		$scope.addMSG = "";
		$http({
			method: 'POST',
			url: '/dist/php/getData.php?action=create',
			data: $.param($scope.formData), // pass in data as strings
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			} // set the headers so angular passing info as form data (not request payload)
		})
			.success(function(data,response) {
			console.log(data);
			

            
			if (!data.success) {
                console.log(data);
                $scope.addMSG = "User failed to be added to the system.";



			} else {

				console.log(data);
				$scope.addMSG = "User was successfully added.";
				$scope.formData={};

			}
		});

	
}

$scope.add_coach = function() {
  $scope.addMSG2 = "";
  $http({
    method: 'POST',
    url: '/dist/php/getData.php?action=addcoach',
    data: $.param($scope.formData), // pass in data as strings
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    } // set the headers so angular passing info as form data (not request payload)
  })
    .success(function(data,response) {
    console.log(data);
    

          
    if (!data.success) {
              console.log(data);
              $scope.addMSG2 = "Coach failed to be added to the system.";



    } else {

      console.log(data);
      $scope.addMSG2 = "Coach was successfully added.";
      $scope.formData={};

    }
  });


}

$scope.update_info = function() {
  $http({
    method: 'POST',
    url: '/dist/php/getData.php?action=updateuser',
    data: $.param($scope.profile), // pass in data as strings
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    } // set the headers so angular passing info as form data (not request payload)
  })
    .success(function(data,response) {
    console.log(data);
    

          
    if (!data.success) {
              console.log(data);
             



    } else {

      console.log(data);
   
      $scope.formData={};

    }
  });


}

$scope.add_family = function() {
  $http({
    method: 'POST',
    url: '/dist/php/getData.php?action=addfamily',
    data: $.param($scope.formData),  
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    } // set the headers so angular passing info as form data (not request payload)
  })
    .success(function(data,response) {
    console.log(data);
    console.log($scope.formData);
    

          
    if (!data.success) {
              console.log(data);
              $scope.addMSG = "User failed to be added to the system.";
              

    } else {

      console.log(data);
      $scope.addMSG = "Encounter was successfully added.";
      $scope.formData={};

    }
  });


}


$scope.add_encounter = function() {
  $http({
    method: 'POST',
    url: '/dist/php/getData.php?action=addencounter',
    data: $.param($scope.formData),  
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    } // set the headers so angular passing info as form data (not request payload)
  })
    .success(function(data,response) {
    console.log(data);
    console.log($scope.formData);
    

          
    if (!data.success) {
              console.log(data);
              $scope.addMSG = "User failed to be added to the system.";
              

    } else {

      console.log(data);
      $scope.addMSG = "Encounter was successfully added.";
      $scope.formData={};

    }
  });


}


   $scope.get_coaches_names = function() {
		$http({
			method: 'GET',
			url: '/dist/php/getData.php?action=getcoach',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
		}).success(function(response) { 
			$scope.tempCoach = response;
			for (i = 0; i < $scope.tempCoach.length; i++) {
				$scope.dbCoaches[i] = $scope.tempCoach[i].first + ' ' + $scope.tempCoach[i].last;
			}
		});
    }
    
  
});

 
 
