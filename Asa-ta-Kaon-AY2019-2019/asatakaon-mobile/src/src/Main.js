import React, 
	{Component} 
	from 'react';
import {
	Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage,
	NetInfo,
	PermissionsAndroid} 
	from 'react-native';
import {Container}   from 'native-base';
import * as firebase from 'firebase';
import SyncStorage   from 'sync-storage';
import RNFetchBlob   from 'react-native-fetch-blob';
import Geolocation   from 'react-native-geolocation-service';

/* === User Created Components  === */

import Constants            from './commons/Constants.js';
import LoadingScreen        from './commons/LoadingScreen.js';
import ReportDisplay        from './commons/ReportDisplay.js';
import WelcomePage          from './commons/WelcomePage.js';
import SplashScreen         from './commons/SplashScreen.js'; 
import FindRestaurant       from './commons/FindRestaurant.js';
import AddFoodEstablishment from './add/AddFoodEstablishment.js';
import LoginPage            from './login/LoginPage.js';
import RegistrationPage     from './registration/RegistrationPage.js';
import HomeDashboard        from './commons/HomeDashboard.js';


export default class Main extends Component{

	state = {
		reportMessage     : '',
		loadingText       : '',
		applicationPages  : Constants.APP_PAGES.SPLASH_SCREEN_APP,
		onlineConnected   : false,
		usersLocation     : {},
		loggedInformation : {},
		onLoggedIn        : false
	}

	/* === For Registration === */
	registerUserCredentials = (userData)=>{
		this.sendAReportMessage('Submitting, please wait...');
		firebase
			.database()
			.ref()
			.child("USERS")
			.orderByChild("username")
			.equalTo(String(userData.username))
			.once("value",snapshot=>{
				if(snapshot.exists()){
					this.sendAReportMessage('The input username is already taken');
					setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
				}
				else{
					firebase
						.database()
						.ref()
						.child("USERS")
						.orderByChild("email")
						.equalTo(String(userData.email))
						.once("value",snapshot=>{
							if(snapshot.exists()){
								this.sendAReportMessage('The input email is already taken');
								setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
							}
							else{
								this.setState({loadingText:'Getting your registration, please wait..'});
								this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
								const userKey = 	firebase
														.database()
														.ref("USERS")
														.push();
								userKey
									.update({
										firstName  : userData.firstName,
										lastName   : userData.lastName,
										address    : userData.address,
										email      : userData.email,
										username   : userData.username,
										password   : userData.password,
										gender     : userData.gender,
										accountID  : String(userKey.key),
										role       : String(Constants.ROLES.USER_ONLY),
										status     : Constants.ACCOUNT_USER_STATUS.ACCEPTED
									})
									.then(()=>{
										this.sendAReportMessage('You have been successfully registered!');
										setTimeout(()=>{
											this.sendAReportMessage('');
											this.setState({loadingText:''});
											this.changeMainApplicationDisplay(Constants.APP_PAGES.FIND_RESTAURANT_APP);
										},Constants.REPORT_DISPLAY_TIME);
										
									})
									.catch((error)=>{
										this.sendAReportMessage('Check your connectivity, you are offline');
										setTimeout(()=>{
											this.sendAReportMessage('');
											this.setState({loadingText:''});
											this.changeMainApplicationDisplay(Constants.APP_PAGES.FIND_RESTAURANT_APP);
										},Constants.REPORT_DISPLAY_TIME);
										
									});
							}
						})
						.catch((error)=>{
							this.sendAReportMessage('Check your connectivity, you are offline');
							setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
						});
				}
			})
			.catch((error)=>{
				this.sendAReportMessage('Check your connectivity, you are offline');
				setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
			});
	}

	registrationRestaurantCredential = (restaurantData)=>{
		this.sendAReportMessage('Submitting, please wait...');
		firebase
			.database()
			.ref()
			.child("RESTAURANT")
			.orderByChild("username")
			.equalTo(String(restaurantData.username))
			.once("value",snapshot=>{
				if(snapshot.exists()){
					this.sendAReportMessage('The input username is already taken');
					setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
				}
				else{
					firebase
						.database()
						.ref()
						.child("USERS")
						.orderByChild("username")
						.equalTo(String(restaurantData.username))
						.once("value",snapshot=>{
							if(snapshot.exists()){
								this.sendAReportMessage('The input username is already taken');
								setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
							}
							else{

								firebase
									.database()
									.ref()
									.child("USERS")
									.orderByChild("email")
									.equalTo(String(restaurantData.email))
									.once("value",snapshot=>{
										if(snapshot.exists()){
											this.sendAReportMessage('The input email is already taken');
											setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
										}
										else{
											firebase
												.database()
												.ref()
												.child("RESTAURANT")
												.orderByChild("email")
												.equalTo(String(restaurantData.email))
												.once("value",snapshot=>{
													if(snapshot.exists()){
														this.sendAReportMessage('The input email is already taken');
														setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
													}
													else{
														this.setState({loadingText:'Getting your registration, please wait..'});
														this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
														const foodKey = 	firebase
																				.database()
																				.ref("RESTAURANT")
																				.push();
														foodKey
															.update({
																restaurantName : restaurantData.restaurantName,
																startingHour   : restaurantData.startingHour,
																closingHour    : restaurantData.closingHour,
																email          : restaurantData.email,
																username       : restaurantData.username,
																password       : restaurantData.password,
																key            : String(foodKey.key),
																role           : String(Constants.ROLES.RESTAURANT_OWNER),
																placeStatus    : Constants.RESTAURANT_PLACE_STATUS.BLOCKED,
																acceptBooking  : 'false' 
															})
															.then(()=>{
																this.sendAReportMessage('You have been successfully registered!');
																setTimeout(()=>{
																	this.sendAReportMessage('');
																	this.setState({loadingText:''});
																	this.changeMainApplicationDisplay(Constants.APP_PAGES.FIND_RESTAURANT_APP);
																},Constants.REPORT_DISPLAY_TIME);
																
															})
															.catch((error)=>{
																this.sendAReportMessage('Check your connectivity, you are offline');
																setTimeout(()=>{
																	this.sendAReportMessage('');
																	this.setState({loadingText:''});
																	this.changeMainApplicationDisplay(Constants.APP_PAGES.FIND_RESTAURANT_APP);
																},Constants.REPORT_DISPLAY_TIME);
																
															});
													}
												})
												.catch((error)=>{
													this.sendAReportMessage('Check your connectivity, you are offline');
													setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
												});
										}
									})
									.catch((error)=>{
										this.sendAReportMessage('Check your connectivity, you are offline');
										setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
									});
							}
						})
						.catch((error)=>{
							this.sendAReportMessage('Check your connectivity, you are offline');
							setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
						});
				}
			})
			.catch((error)=>{
				this.sendAReportMessage('Check your connectivity, you are offline');
				setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
			});
	}

	/* === End === */

	/* == For Login == */
	storeCredentialsLocally = (finalAccountInfo)=>{
		SyncStorage.set(Constants.LOCAL_ACCOUNT_KEY,JSON.stringify(finalAccountInfo))
		.then(()=>{
			console.log('Success storing Sync-Storage');
		})
		.catch((error)=>{
			console.log(error);
		});
	}

	loginProcess = (loginData)=>{
		firebase
			.database()
			.ref()
			.child("USERS")
			.orderByChild("username")
			.equalTo(String(loginData.username))
			.once("value",snapshot=>{
				if(snapshot.exists()){
					let accountInfoWithKey    = JSON.parse(JSON.stringify(snapshot.val()));
					let accountKey            = Object.keys(accountInfoWithKey);
					let finalAccountInfo      = accountInfoWithKey[accountKey];

					if(String(finalAccountInfo.password) == String(loginData.password)){
						this.setState({loadingText:Constants.LOADING_TEXT.LOGGING_IN});
						this.sendAReportMessage('Successfully logged into the application');
						this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
						this.storeCredentialsLocally(finalAccountInfo);
						this.setState({loggedInformation:finalAccountInfo});
						setTimeout(()=>{
							this.sendAReportMessage('');
							this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						},Constants.REPORT_DISPLAY_TIME);
					}
					else{
						console.log('Users!');
						this.sendAReportMessage('Incorrect Username or Password');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					}
				}
				else{
					firebase
						.database()
						.ref()
						.child("RESTAURANT")
						.orderByChild("username")
						.equalTo(String(loginData.username))
						.once("value",snapshot=>{
							if(snapshot.exists()){
								let accountInfoWithKey    = JSON.parse(JSON.stringify(snapshot.val()));
								let accountKey            = Object.keys(accountInfoWithKey);
								let finalAccountInfo      = accountInfoWithKey[accountKey];

								if(String(finalAccountInfo.password) == String(loginData.password)){
									this.setState({loadingText:Constants.LOADING_TEXT.LOGGING_IN});
									this.sendAReportMessage('Successfully logged in to the application');
									this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
									this.storeCredentialsLocally(finalAccountInfo);
									this.setState({loggedInformation:finalAccountInfo});
									setTimeout(()=>{
										this.sendAReportMessage('');
										this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
									},Constants.REPORT_DISPLAY_TIME);
								}	
								else{
									this.sendAReportMessage('Incorrect Username or Password');
									setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
								}
							}
							else{
								console.log('Restaurant!');
								this.sendAReportMessage('Incorrect Username or Password');
								setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
							}
						})
						.catch((error)=>{
							this.sendAReportMessage('Check your connectivity, you are offline');
							setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
						});
				}
			})
			.catch((error)=>{
				this.sendAReportMessage('Check your connectivity, you are offline');
				setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
			});
	}

	checkLocalStoredInformation = ()=>{
		let localAccountInformation = SyncStorage.get(Constants.LOCAL_ACCOUNT_KEY);
		if(localAccountInformation == undefined){
			this.setState({onLoggedIn:false});
			this.setState({loggedInformation:{}});
		}
		else{
			this.setState({onLoggedIn:true});
			this.setState({loggedInformation:JSON.parse(localAccountInformation)});
			firebase
				.database()
				.ref()
				.child("RESTAURANT")
				.orderByChild("username")
				.equalTo(String(JSON.parse(localAccountInformation).username))
				.once("value",snapshot=>{
					if(snapshot.exists()){
						let accountInfoWithKey    = JSON.parse(JSON.stringify(snapshot.val()));
						let accountKey            = Object.keys(accountInfoWithKey);
						let finalAccountInfo      = accountInfoWithKey[accountKey];
						this.storeCredentialsLocally(finalAccountInfo);
						this.setState({loggedInformation:finalAccountInfo});
					}
					else{
						firebase
							.database()
							.ref()
							.child("USERS")
							.orderByChild("username")
							.equalTo(String(JSON.parse(localAccountInformation).username))
							.once("value",snapshot=>{
								if(snapshot.exists()){
									let accountInfoWithKey    = JSON.parse(JSON.stringify(snapshot.val()));
									let accountKey            = Object.keys(accountInfoWithKey);
									let finalAccountInfo      = accountInfoWithKey[accountKey];
									this.storeCredentialsLocally(finalAccountInfo);
									this.setState({loggedInformation:finalAccountInfo});
								}
								else;
							})
							.then(()=>{
								console.log('Updated locally from cloud');

							})
							.catch((error)=>{
								console.log('SyncStorage Error: '+String(error));
							});
					}
				})
				.then(()=>{
					console.log('Updated locally from cloud');

				})
				.catch((error)=>{
					console.log('SyncStorage Error: '+String(error));
				});
		}
		this.controlSplashScreen();
	}

	/* === End === */	

	/* === For Restaurant Owners == */

	submitRestaurantOwnersLocation = (location)=>{
		this.sendAReportMessage('Setting as your final address, please wait..');
		setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		this.setState({loadingText:'Submitting changes, please wait..'});
		this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
		firebase
			.database()
			.ref("RESTAURANT/"+String(this.state.loggedInformation.key)+"/location")
			.update({
				'addressName' : location.addressName,
				'latitude'    : location.latitude,
				'longitude'   : location.longitude
			})
			.then(()=>{
				firebase
					.database()
					.ref("RESTAURANT/"+String(this.state.loggedInformation.key))
					.once("value",snapshot=>{
						if(snapshot.exists()){
							const ownersAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
							this.storeCredentialsLocally(ownersAccountInformation);
							this.setState({loggedInformation:ownersAccountInformation});
						}
						else{
							this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
							this.sendAReportMessage('An error has occured in the server');
							setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);	
						}
					})
					.then(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('Successfully updated your restaurant location');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					});
				
			})
			.catch((error)=>{
				this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
				this.sendAReportMessage('Error occured in connecting to server');
				setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
			});
	}

	addNewDishInMenu = (dish)=>{
		this.sendAReportMessage('Adding in your menu, please wait..');
		setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		this.setState({loadingText:'Submitting information, please wait..'});
		this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
		const dishKey = 	firebase
								.database()
								.ref("RESTAURANT/"+String(this.state.loggedInformation.key)+"/Menu")
								.push();
		dishKey
			.update({
				name        : dish.name,
				foodType    : dish.foodType,
				price       : dish.price,
				description : dish.dishDescription,
				persons     : dish.numberOfPersons,
				key         : String(dishKey.key)
			})
			.then(()=>{
				firebase
					.database()
					.ref("RESTAURANT/"+String(this.state.loggedInformation.key))
					.once("value",snapshot=>{
						if(snapshot.exists()){
							const updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
							this.storeCredentialsLocally(updatedAccountInformation);
							this.setState({loggedInformation:updatedAccountInformation});
						}
					})
					.then(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('Successfully added a dish in your menu');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					});
			})
			.catch((error)=>{
				this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
				this.sendAReportMessage('Error occured in connecting to server');
				setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
			});
	}

  	deleteADishInMenu = (dishInformation)=>{
  		this.sendAReportMessage('Removing your dish, please wait..');
		firebase
			.database()
			.ref("RESTAURANT/"
				+String(this.state.loggedInformation.key)
				+"/Menu/"
				+String(dishInformation.key))
			.remove()
			.then(()=>{
				firebase
				.database()
				.ref("RESTAURANT/"+String(this.state.loggedInformation.key))
				.once("value",snapshot=>{
					if(snapshot.exists()){
						const updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
						this.storeCredentialsLocally(updatedAccountInformation);
						this.setState({loggedInformation:updatedAccountInformation});
					}
					else{
						this.sendAReportMessage('Succesfully removed in your menu');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					}
				})
				.then(()=>{
					this.sendAReportMessage('Succesfully removed in your menu');
					setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
				});
  			})
  			.catch((error)=>{
  				this.sendAReportMessage('Error in conneting to the server');
  				setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
  			});
  	}

	/* === End === */

	userChangePassword = (newPassword)=>{
		this.sendAReportMessage('Updating your password, please wait..');
		setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		this.setState({loadingText:'Submitting changes, please wait..'});
		this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
		if(this.state.loggedInformation.role == Constants.ROLES.USER_ONLY){
			firebase
				.database()
				.ref("USERS/"+String(this.state.loggedInformation.accountID))
				.update({password:String(newPassword)})
				.then(()=>{
					firebase
						.database()
						.ref("USERS/"+String(this.state.loggedInformation.accountID))
						.once("value",snapshot=>{
							if(snapshot.exists()){
								let updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
								this.storeCredentialsLocally(updatedAccountInformation);
								this.setState({loggedInformation:updatedAccountInformation});
							}
						})
						.then(()=>{
							console.log('Updated locally from cloud');
						})
						.catch((error)=>{
							console.log('SyncStorage Error: '+String(error));
						});
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('Successfully updated your password');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('An error occured in upadting your password');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else if(this.state.loggedInformation == Constants.ROLES.RESTAURANT_OWNER){
			firebase
				.database()
				.ref("RESTAURANT/"+String(this.state.loggedInformation.key))
				.update({password:String(newPassword)})
				.then(()=>{
					firebase
						.database()
						.ref("RESTAURANT/"+String(this.state.loggedInformation.key))
						.once("value",snapshot=>{
							if(snapshot.exists()){
								let updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
								this.storeCredentialsLocally(updatedAccountInformation);
								this.setState({loggedInformation:updatedAccountInformation});
							}
						})
						.then(()=>{
							console.log('Updated locally from cloud');
						})
						.catch((error)=>{
							console.log('SyncStorage Error: '+String(error));
						});
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('Successfully updated your password');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('An error occured in upadting your password');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
	}

	updateUserInformation = (data)=>{
		this.sendAReportMessage('Updating your account, please wait..');
		setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		this.setState({loadingText:'Submitting changes, please wait..'});
		this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
		if(this.state.loggedInformation.role == Constants.ROLES.USER_ONLY){
			firebase
				.database()
				.ref("USERS/"+String(this.state.loggedInformation.accountID))
				.update({
					firstName : data.firstName,
					lastName  : data.lastName,
					address   : data.address,
					email     : data.email
				})
				.then(()=>{
					firebase
						.database()
						.ref("USERS/"+String(this.state.loggedInformation.accountID))
						.once("value",snapshot=>{
							if(snapshot.exists()){
								let updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
								this.storeCredentialsLocally(updatedAccountInformation);
								this.setState({loggedInformation:updatedAccountInformation});
							}
						})
						.then(()=>{
							console.log('Updated locally from cloud');
						})
						.catch((error)=>{
							console.log('SyncStorage Error: '+String(error));
						});
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('Successfully updated account details');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('An error occured in upadting your details');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else if(this.state.loggedInformation.role == Constants.ROLES.RESTAURANT_OWNER){
			firebase
				.database()
				.ref("RESTAURANT/"+String(this.state.loggedInformation.accountID))
				.update({
					email : data.email
				})
				.then(()=>{
					firebase
						.database()
						.ref("RESTAURANT/"+String(this.state.loggedInformation.accountID))
						.once("value",snapshot=>{
							if(snapshot.exists()){
								let updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
								this.storeCredentialsLocally(updatedAccountInformation);
								this.setState({loggedInformation:updatedAccountInformation});
							}
						})
						.then(()=>{
							console.log('Updated locally from cloud');
						})
						.catch((error)=>{
							console.log('SyncStorage Error: '+String(error));
						});
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('Successfully updated account details');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					setTimeout(()=>{
						this.setState({loadingText:''});
						this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
						this.sendAReportMessage('An error occured in upadting your details');
						setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
	}


	componentDidMount(){
		this.initializeLocalStorage();
		if(!firebase.apps.length){
			firebase.initializeApp(Constants.FIRE_BASE_CONFIG);
		}
		NetInfo.isConnected.addEventListener('connectionChange',this.handleDataConnectivity);
		this.askUserGPSPermission();
	}

	signOutAccount = ()=>{
		this.setState({loadingText:Constants.LOADING_TEXT.LOGGING_OUT});
		this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
		SyncStorage.remove(Constants.LOCAL_ACCOUNT_KEY);
		setTimeout(()=>{
			this.setState({loadingText:''});
			this.changeMainApplicationDisplay(Constants.APP_PAGES.WELCOME_APP_PAGE);
			this.sendAReportMessage('Successfully logged out account');
			setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		},Constants.REPORT_DISPLAY_TIME);
	}


	initializeLocalStorage = async()=>{
		const data = await SyncStorage.init();
		this.checkLocalStoredInformation();
	}


	askUserGPSPermission = async()=>{
		try {
   			const granted = await PermissionsAndroid.request(
   				PermissionsAndroid.PERMISSIONS.ACCESS_FINE_LOCATION,
			 	{
			        title: 'Location Persistence',
			        message:
			          'AsaTaKaon application needs access to your location',
			        buttonNegative: 'Cancel',
			        buttonPositive: 'OK',
  				}
			);
			if(granted === PermissionsAndroid.RESULTS.GRANTED){
		      	console.log('You can now use the user location');
		      	this.getUserLocation();
		    } 
		    else{
		      console.log('Location permission denied');
		    }
		} 
		catch(error){
    		console.log(error);
	  	}	
	}

	getUserLocation = ()=>{
		Geolocation.getCurrentPosition( (position)=>{
			console.log('Got location');
			this.setState({usersLocation:position.coords});
		},(error) => {
			console.log(JSON.stringify(error));
		},{enableHighAccuracy: true});
		
		Geolocation.watchPosition( (position)=>{
			console.log('Got location updated');
			this.setState({usersLocation:position.coords});
		},(error) => {
			console.log(JSON.stringify(error));
		},{enableHighAccuracy: true,distanceFilter:10,fastestInterval:5000});
	}

	handleDataConnectivity = (isConnected)=>{
		if(isConnected === true){
			this.sendAReportMessage(Constants.LOADING_TEXT.GETTING_CONNECTED);
			fetch('https://jsonplaceholder.typicode.com/todos/1')
		  		.then(response =>{
		  			this.setState({onlineConnected: true});
		  			this.sendAReportMessage(Constants.LOADING_TEXT.USER_CONNECTED);
					setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		  		})
		  		.catch((error)=>{
		  			this.setState({onlineConnected: false});
					this.sendAReportMessage(Constants.LOADING_TEXT.USER_OFFLINE);
					setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		  		});
		}
		else{
			this.setState({onlineConnected: false});
			this.sendAReportMessage(Constants.LOADING_TEXT.USER_OFFLINE);
			setTimeout(()=>this.sendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		}
	}

	controlSplashScreen = ()=>{

		setTimeout(()=>{
			this.setState({loadingText:''});
			if(this.state.onLoggedIn == true){
				this.setState({loadingText:Constants.LOADING_TEXT.LOGGING_IN});
				this.changeMainApplicationDisplay(Constants.APP_PAGES.LOADING_SCREEN_APP);
				setTimeout(()=>{
					this.setState({loadingText:''});
					this.changeMainApplicationDisplay(Constants.APP_PAGES.HOME_DASHBOARD);
				},Constants.REPORT_DISPLAY_TIME);
			}
			else{
				this.setState({applicationPages:Constants.APP_PAGES.WELCOME_APP_PAGE});
			}
		},Constants.TIME.SPLASH_SCREEN_TIME);
	}

	changeMainApplicationDisplay = (applicationPages)=>{
		this.setState({applicationPages:applicationPages});
	}

	setLoggedInformation = (accountInformation)=>{
		this.setState({loggedInformation:accountInformation});
		this.storeCredentialsLocally(accountInformation);
	}


	MainApplicationDisplay = ()=>{
		switch(this.state.applicationPages){
			case Constants.APP_PAGES.LOADING_SCREEN_APP:
				return 	<LoadingScreen 
							loadingText            = {this.state.loadingText} />
			case Constants.APP_PAGES.WELCOME_APP_PAGE:
				return 	<WelcomePage
							doChangeMainAppDisplay = {this.changeMainApplicationDisplay} />;
			case Constants.APP_PAGES.SPLASH_SCREEN_APP:
				return 	<SplashScreen/>;
			case Constants.APP_PAGES.FIND_RESTAURANT_APP:
				return 	<FindRestaurant
							doUseFirebaseObject    = {firebase}
							doGetUsersLocation     = {this.state.usersLocation}
							doChangeMainAppDisplay = {this.changeMainApplicationDisplay} />;
			case Constants.APP_PAGES.LOGIN_APP_PAGE:
				return 	<LoginPage 
							doSubmitLoginInput     = {this.loginProcess}
							doSendAReportMessage   = {this.sendAReportMessage}
							doChangeMainAppDisplay = {this.changeMainApplicationDisplay} />;
			case Constants.APP_PAGES.SIGN_APP_PAGE:
				return 	<RegistrationPage
							doRegisterUser         = {this.registerUserCredentials}
							doSendAReportMessage   = {this.sendAReportMessage}
							doChangeMainAppDisplay = {this.changeMainApplicationDisplay} />;
			case Constants.APP_PAGES.SIGN_RESTAURANT:
				return 	<AddFoodEstablishment
							doRegisterRestaurant   = {this.registrationRestaurantCredential}
							doChangeMainAppDisplay = {this.changeMainApplicationDisplay}
							doSendAReportMessage   = {this.sendAReportMessage}
							doChangeMainAppDisplay = {this.changeMainApplicationDisplay} />;
			case Constants.APP_PAGES.HOME_DASHBOARD:
				return 	<HomeDashboard
							doUseFirebaseObject    = {firebase}
							doSignOutAccount       = {this.signOutAccount}
							doGetUsersLocation     = {this.state.usersLocation}
							doSendAReportMessage   = {this.sendAReportMessage}
							doSetLoggedInformation = {this.setLoggedInformation}
							doGetLoggedInformation = {this.state.loggedInformation}
							doChangeUserPassword   = {this.userChangePassword}
							doSetRestaurantAddress = {this.submitRestaurantOwnersLocation}
							doAddANewDish          = {this.addNewDishInMenu}
							doDeleteADishMenu      = {this.deleteADishInMenu}
							doUpdateUserInfo       = {this.updateUserInformation} />;
		}
	}

	reportApplicationDisplay = ()=>{
		if(this.state.reportMessage.length!=0){
			return 	<ReportDisplay
						reportMessage = {this.state.reportMessage} />
		}
		else{
			return;
		}
	}

	sendAReportMessage = (report)=>{
		this.setState({reportMessage:report});
	}

	render() {
	    return (
	    	<View style={{flex:1}}>
	    		{this.MainApplicationDisplay()}
	    		{this.reportApplicationDisplay()}
	    	</View>
	    );
  	}
}

