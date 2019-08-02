import React, 
	{Component} 
	from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage,
	Image,
	NetInfo,
	TouchableWithoutFeedback,
	TextInput,
	Picker,
	CheckBox,
	FlatList} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
/* -- Custom Components  -- */

import Constants   from '../commons/Constants.js';

export default class BookingsPage extends Component{
	
	state = {
		restaurantMenu        : [],
		requestsMade          : [],
		loadingRequestData    : true,
		firebaseAccountObject : ''
	}	

	componentDidMount(){
		const accountFirebaseObject = 	this.props.doUseFirebaseObject
											.database()
											.ref("USERS/"+String(this.props.doGetLoggedInformation.accountID))
											.on("value",snapshot=>{
												if(snapshot.exists()){
													this.setState({loadingRequestData:true});
													const updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
													this.props.doSetLoggedInformation(updatedAccountInformation);
													this.getAllRequestsMade();
												}
											});
		if(this.props.doGetRestaurantDetails.Menu){
			const dishesWithKey      = JSON.parse(JSON.stringify(this.props.doGetRestaurantDetails.Menu));
			const initRestaurantMenu = [];
			Object
				.keys(dishesWithKey)
				.forEach((dishKey)=>{
					initRestaurantMenu.push(dishesWithKey[dishKey]);
				});
			this.setState({restaurantMenu:initRestaurantMenu});
		}	
		this.getAllRequestsMade();
		this.setState({firebaseAccountObject:accountFirebaseObject});
	}

	componentWillUnmount(){
		this.props.doUseFirebaseObject
			.database()
			.ref("USERS/"+String(this.props.doGetLoggedInformation.accountID))
			.off("value",this.state.firebaseAccountObject);
	}

	getAllRequestsMade = ()=>{
		if(this.props.doGetLoggedInformation.requests){
			const allRequestsWithKey = JSON.parse(JSON.stringify(this.props.doGetLoggedInformation.requests));
			const initRequestsMade   = [];
			Object
				.keys(allRequestsWithKey)
				.forEach((reqKey)=>{
					initRequestsMade.push(allRequestsWithKey[reqKey]);
				});
			this.setState({requestsMade:initRequestsMade});
			this.setState({loadingRequestData:false});
		}
		else{
			this.setState({
				loadingRequestData : false,
				requestsMade       : []});
		} 
	}

	searchForRequestsMade = ()=>{
		if(this.state.loadingRequestData == true){
			this.props.doSendAReportMessage('Information is not available, try again later');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
			return;
		}
		else{
			console.log(this.state.requestsMade);
			for(index=0;index<this.state.requestsMade.length;index++){
				if( this.state.requestsMade[index].status == Constants.BOOKING_STATUS.PENDING
					&& this.state.requestsMade[index].restaurantKey == this.props.doGetRestaurantDetails.key){
					return 1; // pending
				}
				else if( this.state.requestsMade[index].status == Constants.BOOKING_STATUS.BOOKED
					&& this.state.requestsMade[index].restaurantKey == this.props.doGetRestaurantDetails.key){
					return 2; // booked
				}
				else if( this.state.requestsMade[index].status == Constants.BOOKING_STATUS.CLAIMED
					&& this.state.requestsMade[index].restaurantKey == this.props.doGetRestaurantDetails.key){
					return 4; // claimed
				}
			}
			return 3; // cancelled or not requested yet
		}
	}

	sendRequest = ()=>{
		this.props.doSendAReportMessage('Submitting your request, please wait..');
		const permissionFlag = this.searchForRequestsMade();
		const today          = new Date();
		const hour           = 	today.getHours() > 12 ? Number(today.getHours()-12): 
									(today.getHours() == 0 ? '12' : today.getHours()) ;
		const AMorPM         = 	today.getHours() > 11 ? 'PM': 'AM';
		const StringDate     = 	String(hour)
									+':'
									+ ( Number(today.getMinutes())>9 ? 
										String(today.getMinutes()) : '0'+String(today.getMinutes()) )
									+' '
									+String(AMorPM)
									+' '
									+String(today.getMonth()+1)
									+'/'
									+String(today.getDate())
									+'/'
									+String(today.getFullYear());
		if( permissionFlag == 3){
			const requestObject = 	this.props.doUseFirebaseObject
										.database()
										.ref("RESTAURANT/"+String(this.props.doGetRestaurantDetails.key)+"/requests")
										.push();
			requestObject
				.update({
					'fullName'      : String(this.props.doGetLoggedInformation.firstName
										+' '+this.props.doGetLoggedInformation.lastName), 
					'userKey'       : String(this.props.doGetLoggedInformation.accountID),
					'requestkey'    : String(requestObject.key),
					'email'         : String(this.props.doGetLoggedInformation.email),
					'status'        : Constants.BOOKING_STATUS.PENDING,
					'gender'        : String(this.props.doGetLoggedInformation.gender),
					'restaurantKey' : String(this.props.doGetRestaurantDetails.key),
					'time'          : String(StringDate)
				})
				.then(()=>{
					this.props.doUseFirebaseObject
						.database()
						.ref("USERS/"
							+String(this.props.doGetLoggedInformation.accountID)
							+"/requests/"
							+String(requestObject.key))
						.update({
							'requestkey'        : String(requestObject.key),
							'restaurantName'    : String(this.props.doGetRestaurantDetails.restaurantName),
							'restaurantAddress' : String(this.props.doGetRestaurantDetails.location.addressName),
							'status'            : Constants.BOOKING_STATUS.PENDING,
							'restaurantKey'     : String(this.props.doGetRestaurantDetails.key),
							'startingHour'      : String(this.props.doGetRestaurantDetails.startingHour),
							'closingHour'       : String(this.props.doGetRestaurantDetails.closingHour),
							'time'              : String(StringDate)
						})
						.then(()=>{
							this.props.doUseFirebaseObject
								.database()
								.ref("USERS/"+String(this.props.doGetLoggedInformation.accountID))
								.once("value",snapshot=>{
									if(snapshot.exists()){
										const updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
										this.props.doSetLoggedInformation(updatedAccountInformation);
									}
								})
								.then(()=>{
									this.props.doSendAReportMessage('Successfully sent your request');
									setTimeout(()=>{
										this.props.doSetHomePage(Constants.USER_ROLE_PAGES.LANDING_PAGE);
									},700);
									setTimeout(()=>{
										this.props.doSendAReportMessage('');
									},Constants.REPORT_DISPLAY_TIME);	
								});
						});
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('Error occured in connecting to the server');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else if( permissionFlag == 1){
			this.props.doSendAReportMessage('You already sent a booking request');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if( permissionFlag == 2){
			this.props.doSendAReportMessage('You are already accepted in this booking');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
	}

	render() {
	    return (
	    	<View style={{
    				height:'100%',
    				width:'100%',
    				position:'relative',
    				alignItems:'center'
    		}}>
    			<View style={{
    					height: '8.5%',
    					width:'100%',
    					position:'relative',
					    borderColor: '#ddd',
					    borderBottomWidth: 0,
					    shadowColor: '#000',
					    shadowOffset: {
							width: 0,
							height: 5,
						},
						shadowOpacity: 0.34,
						shadowRadius: 6.27,
						elevation: 9,
					    backgroundColor: '#555dff',
					    flexDirection: 'row'
    			}}>
    				<TouchableWithoutFeedback
    					onPress={()=>this.props.doSetHomePage(Constants.USER_ROLE_PAGES.LANDING_PAGE)}>
	    				<Text style={{
		    					height: '50%',
		    					width: '18%',
		    					position: 'relative',
		    					color: '#000',
		    					fontSize: 13,
		    					fontWeight: 'bold',
		    					textAlign: 'center',
		    					textAlignVertical: 'center',
		    					borderRadius: 100,
		    					borderWidth:2,
		    					left: '10%',
		    					top: '3%'
		    			}}>
		    				RETURN
		    			</Text>
		    		</TouchableWithoutFeedback>

    				<Text style={{
    						height:'100%',
    						width:'50%',
    						textAlign:'center',
    						textAlignVertical:'center',
    						fontSize:15,
    						fontWeight:'bold',
    						color:'#000',
    						left: '50%'
    				}}>
    					Restaurant Details
    				</Text>
    			</View>

    			<View style ={{
    					height: '30%',
    					width: '100%',
    					position:'relative',
    					top: '1.5%'
    			}}>
    				<Text style ={{
    						height: '30%',
    						top: '30%',
    						width: '100%',
    						textAlign:'center',
    						textAlignVertical:'center',
    						fontSize: 11.5,
    						fontWeight: 'bold',
    						color: '#000',
    						position:'absolute'
    				}}>
    					{
    						this.props.doGetRestaurantDetails.displayIMG ? 
    						'Loading image..':'No image added'
    					}
    				</Text>
    				
    				<Image
    					source = {{uri:this.props.doGetRestaurantDetails.displayIMG}}
    					style = {{
    						height: '100%',
    						width:'100%',
    						resizeMode: 'contain',
    						position:'relative'
    					}}/>
    			</View>
    			<Text style ={{
    					height: 17,
    					width: '90%',
    					textAlignVertical:'center',
    					fontSize: 12,
    					top: '2%',
    					color: '#000'
    			}}>
    				{this.props.doGetRestaurantDetails.restaurantName}
    			</Text>
    			<Text style ={{
    					height: 16,
    					width: '90%',
    					textAlignVertical:'center',
    					fontSize: 11.5,
    					top: '2%',
    					color: '#000',
    					fontWeight: 'bold'
    			}}>
    				{'Current price range in pesos: '+(
    					this.props.doGetRestaurantDetails.priceRange ? 
    					(this.props.doGetRestaurantDetails.priceRange.minimum
    						+'-'
    						+this.props.doGetRestaurantDetails.priceRange.maximum) : 'Not updated by owner')}
				</Text>
    			<Text style ={{
    					height: 16,
    					width: '90%',
    					textAlignVertical:'center',
    					fontSize: 12,
    					top: '2%',
    					color: '#000'
    			}}>
    				{'Operating hours: '
    				+this.props.doGetRestaurantDetails.startingHour
    				+'-'
    				+this.props.doGetRestaurantDetails.closingHour}
    			</Text>
    			<Text style ={{
    					height: 34,
    					width: '90%',
    					textAlignVertical:'center',
    					fontSize: 12,
    					top: '2%',
    					color: '#000'
    			}}>
    				{'Address: '+this.props.doGetRestaurantDetails.location.addressName}
    			</Text>
    			<Text style ={{
    					height: 18,
    					width: '90%',
    					top: '2%',
    					fontSize:13,
    					fontWeight:'bold',
    					fontStyle: 'italic',
    					textAlignVertical:'center',
    					color: '#000'
    			}}>
    				Our menu
    			</Text>
    			<View style ={{
    					height: '33%',
    					width: '100%',
    					position: 'relative',
    					top:'2%'
    			}}>
	    			{
	    				!this.props.doGetRestaurantDetails.Menu ?
	    				<Text style ={{
	    						height: '15%',
	    						width: '100%',
	    						position: 'relative',
	    						fontSize: 13,
	    						textAlignVertical:'center',
	    						textAlign:'center',
	    						color: '#000',
	    						top: '40%',
	    						fontWeight: 'bold'
	    				}}>
	    					No dish have been added by the owner
	    				</Text>:
	    				(
	    					this.state.restaurantMenu.length == 0 ?
	    					<Text style ={{
		    						height: '15%',
		    						width: '100%',
		    						position: 'relative',
		    						fontSize: 13,
		    						textAlignVertical:'center',
		    						textAlign:'center',
		    						color: '#000',
		    						top: '40%',
		    						fontWeight: 'bold'
		    				}}>
		    					Getting information, please wait..
		    				</Text>:
		    				<FlatList
								data = {this.state.restaurantMenu}
								renderItem = {({item}) =>
									<View style ={{
											height: 105,
											width: '95%',
											position:'relative',
											marginBottom: 10,
											marginTop   : 10,
											alignItems:'center',
											left: '2.5%',
											borderBottomWidth: 2
									}}>
										<Text style ={{
												height: '20%',
												width: '100%',
												textAlignVertical: 'center',
												textAlign: 'center',
												fontSize: 13,
												color: '#000'
										}}>
											{item.name}
										</Text>
										<Text style ={{
												height: '20%',
												width: '100%',
												textAlignVertical: 'center',
												textAlign: 'center',
												fontSize: 13,
												color: '#000'
										}}>
											{item.foodType}
										</Text>
										<Text style ={{
												height: '20%',
												width: '100%',
												textAlignVertical: 'center',
												textAlign: 'center',
												fontSize: 13,
												color: '#000'
										}}>
											{
												item.description.length == 0?
												'No description added':item.description
											}
										</Text>
										<Text style ={{
												height: '20%',
												width: '100%',
												textAlignVertical: 'center',
												textAlign: 'center',
												fontSize: 13,
												color: '#000'
										}}>
											{'Price in pesos: '+item.price}
										</Text>
										<Text style ={{
												height: '20%',
												width: '100%',
												textAlignVertical: 'center',
												textAlign: 'center',
												fontSize: 13,
												color: '#000'
										}}>
											{'Good for '+item.persons}
										</Text>
									</View>
								}
								keyExtractor={item => item.key}/>
		    			)
		    		}
		    	</View>
    			{
					this.props.doGetRestaurantDetails.acceptBooking == 'false' ? 
	    				<Text style ={{
	    						height: '7%',
	    						width: '60%',
	    						position: 'relative',
	    						fontSize: 13,
	    						textAlignVertical: 'center',
	    						textAlign: 'center',
	    						color: '#000',
	    						fontWeight: 'bold',
	    						borderRadius: 100,
	    						borderWidth: 2,
	    						top: '3%'
	    				}}>
	    					Does not accept bookings
	    				</Text>:
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.sendRequest()}>
		    				<Text style ={{
		    						height: '7%',
		    						width: '40%',
		    						position: 'relative',
		    						fontSize: 13,
		    						textAlignVertical: 'center',
		    						textAlign: 'center',
		    						color: '#000',
		    						fontWeight: 'bold',
		    						borderRadius: 100,
		    						top: '3%',
		    						borderColor: '#ddd',
								    borderBottomWidth: 0,
								    shadowColor: '#000',
								    shadowOffset: {
										width: 0,
										height: 5,
									},
									shadowOpacity: 0.34,
									shadowRadius: 3.27,
									elevation: 10,
								    backgroundColor: '#fff'
		    				}}>
		    					Send a booking
		    				</Text>
		    			</TouchableWithoutFeedback>
    			}
    			<Text style = {{
    					height: '6%',
    					width: '10%',
    					position: 'absolute',
    					fontSize: 14,
    					top: '93%',
    					fontWeight: 'bold',
    					color: '#000',
    					textAlignVertical: 'center',
    					textAlign: 'center',
    					left: '2%'
    			}}>
    				<Icon 
    					style ={{fontSize:20}}
    					name = 'caretdown'
    					type = 'AntDesign'/>
    			</Text>
    		</View>
    	);
	}
}