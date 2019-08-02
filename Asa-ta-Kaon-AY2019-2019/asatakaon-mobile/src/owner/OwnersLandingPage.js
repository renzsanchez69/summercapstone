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
	Switch,
	FlatList} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
import SyncStorage   from 'sync-storage';
import MapView       from 'react-native-maps';
import {Marker}      from 'react-native-maps';

/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';

const ownerIcon = require('../img/icon/owner.png');

export default class OwnersLandingPage extends Component{

	state = {
		inputAcceptBookingFlag     : false,
		loading                    : true,
		tracksViewChangesOwnerIcon : true,
		restaurantAccountObject    : '',
		requestsReceived           : [],
		loadingRequestsData        : true,
		showRequestsReceived       : false,
		allowToggleBooking         : true,
		alarmRequestNotification   : false
	};

	

	componentDidMount(){
		this.getRequestsReceived();
		this.props.doUseFirebaseObject
			.database()
			.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key)+"/acceptBooking")
			.once("value",snapshot=>{
				if(snapshot.exists()){
					if(snapshot.val() == 'true'){
						this.setState({inputAcceptBookingFlag:true});
					}	
					else{
						this.setState({inputAcceptBookingFlag:false});
					}
				}
			})
			.then(()=>{
				this.setState({loading:false});
			})
			.catch((error)=>{
				console.log(error);
			});
		const firebaseObject = 	this.props.doUseFirebaseObject
									.database()
									.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key))
									.on("value",snapshot=>{
										if(snapshot.exists()){	
											this.setState({loadingRequestsData:true});
											const updateAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
											this.props.doSetLoggedInformation(updateAccountInformation);
											this.getRequestsReceived();
										}
									});
		this.setState({restaurantAccountObject:firebaseObject});
	}

	getRequestsReceived = ()=>{
		this.setState({alarmRequestNotification:false});
		if(this.props.doGetLoggedInformation.requests){
			const requestsReceivedWithKey = JSON.parse(JSON.stringify(this.props.doGetLoggedInformation.requests));
			const initRequestsReceived    = [];
			Object
				.keys(requestsReceivedWithKey)
				.forEach((reqReceiveKey)=>{
					if(requestsReceivedWithKey[reqReceiveKey].status == Constants.BOOKING_STATUS.PENDING
						|| requestsReceivedWithKey[reqReceiveKey].status == Constants.BOOKING_STATUS.BOOKED){
						this.setState({ // check if there are current requests or bookings to allow toggle in accepting bookings 
							allowToggleBooking  :false,
							loadingRequestsData :false});
					}

					if(requestsReceivedWithKey[reqReceiveKey].status == Constants.BOOKING_STATUS.PENDING){
						initRequestsReceived.push(requestsReceivedWithKey[reqReceiveKey]);
						this.setState({alarmRequestNotification:true});
					}
				});
			this.setState({requestsReceived:initRequestsReceived});
		}
		else{
			this.setState({
				requestsReceived:[],
				loadingRequestsData:false});
		}
	}

	componentWillUnmount(){
		this.props.doUseFirebaseObject
			.database()
			.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key))
			.off("value",this.state.restaurantAccountObject);
	}


	handleChangeInAcceptBooking =()=>{
		this.props.doSendAReportMessage('Submitting changes, please wait..');
		this.getRequestsReceived();
		if(this.state.loadingRequestsData == false && this.state.allowToggleBooking == true){
			const acceptBooking = String(!this.state.inputAcceptBookingFlag);
			this.setState({loading:true});
			this.props.doUseFirebaseObject
				.database()
				.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key))
				.update({
					'acceptBooking': acceptBooking
				})
				.then(()=>{
					this.setState({inputAcceptBookingFlag:!this.state.inputAcceptBookingFlag});
					this.setState({loading:false});
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('An error has occured, try again');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else if(this.state.loadingRequestsData == true){
			this.props.doSendAReportMessage('Operation is invalid, some data are not available');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			this.props.doSendAReportMessage('Invalid, you have recieved requests or present bookings');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
	}

	onLoadOwnerIcon = ()=>{
		if(ownerIcon){
			setTimeout(()=>{
				this.setState({tracksViewChangesOwnerIcon:false});
			},1500);
		}
	}

	rejectRequest = (request)=>{
		const today          = 	new Date();
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

		this.props.doSendAReportMessage('Submitting, please wait..');
		this.props.doUseFirebaseObject
			.database()
			.ref("USERS/"+String(request.userKey)
				+"/requests/"
				+String(request.requestkey))
			.update({
				'status'          : Constants.BOOKING_STATUS.DENIED,
				'dateRejected'    : StringDate
			})
			.then(()=>{
				const notificationKey = 	this.props.doUseFirebaseObject
												.database()
												.ref("USERS/"+String(request.userKey)+"/notifications/")
												.push();
				notificationKey
					.update({
						'message' : 'Your booking request to '
										+String(this.props.doGetLoggedInformation.restaurantName)
										+' had been declined',
						'date'    : StringDate,
						'status'  : Constants.NOTIFICATION_STATUS.UNREAD,
						'key'     : String(notificationKey.key)
					})
					.then(()=>{
						this.props.doUseFirebaseObject
							.database()
							.ref("RESTAURANT/"
								+String(this.props.doGetLoggedInformation.key)
								+"/requests/"
								+String(request.requestkey))
							.update({
								'status'         : Constants.BOOKING_STATUS.DENIED,
								'dateRejected'   : StringDate
							})
							.then(()=>{
								this.props.doSendAReportMessage('Rejected');
								setTimeout(()=>{
									this.props.doSendAReportMessage('');
									this.setState({showRequestsReceived:false});
								},1500);
							});
					});
				
			})
			.catch((error)=>{
				this.props.doSendAReportMessage('Error in connecting to the server');
				setTimeout(()=>{
					this.props.doSendAReportMessage('');
				},Constants.REPORT_DISPLAY_TIME);
			});
	}

	acceptRequest = (request)=>{
		const today          = 	new Date();
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

		this.props.doSendAReportMessage('Submitting, please wait..');
		this.props.doUseFirebaseObject
			.database()
			.ref("USERS/"+String(request.userKey)
				+"/requests/"
				+String(request.requestkey))
			.update({
				'status'         : Constants.BOOKING_STATUS.BOOKED,
				'dateAccepted'   : StringDate
			})
			.then(()=>{
				const notificationKey = 	this.props.doUseFirebaseObject
												.database()
												.ref("USERS/"+String(request.userKey)+"/notifications/")
												.push();
				notificationKey
					.update({
						'message' : 'Your booking request to '
										+String(this.props.doGetLoggedInformation.restaurantName)
										+' had been accepted',
						'date'    : StringDate,
						'status'  : Constants.NOTIFICATION_STATUS.UNREAD,
						'key'     : String(notificationKey.key)
					})
					.then(()=>{
						this.props.doUseFirebaseObject
							.database()
							.ref("RESTAURANT/"
								+String(this.props.doGetLoggedInformation.key)
								+"/requests/"
								+String(request.requestkey))
							.update({
								'status'         : Constants.BOOKING_STATUS.BOOKED,
								'dateAccepted'   : StringDate
							})
							.then(()=>{
								this.props.doSendAReportMessage('Booked');
								setTimeout(()=>{
									this.props.doSendAReportMessage('');
									this.setState({showRequestsReceived:false});
								},1500);
							});
					});
				
			})
			.catch((error)=>{
				this.props.doSendAReportMessage('Error in connecting to the server');
				setTimeout(()=>{
					this.props.doSendAReportMessage('');
				},Constants.REPORT_DISPLAY_TIME);
			});
	}

	displayRestaurantLocation = ()=>{
		if(this.props.doGetLoggedInformation.placeStatus!=Constants.RESTAURANT_PLACE_STATUS.ACCEPTED){
			return 	<Text style ={{
							height: '24%',
							width: '83%',
							textAlignVertical:'center',
							textAlign: 'center',
							fontSize: 13,
							color: '#000',
							top: '37%'
					}}>
						{'You are blocked, please wait for the admin to validate and accept your account'}
					</Text>
		}
		else if(!this.props.doGetLoggedInformation.location){
			return 	<Text style ={{
							height: '20%',
							width: '80%',
							textAlignVertical:'center',
							textAlign: 'center',
							fontSize: 13.5,
							color: '#000',
							top: '37%'
					}}>
						Please set your location in your restaurant information section
					</Text>
		}
		else{
			return 	<MapView style = {{height:'100%',width: '100%'}}
						provider={MapView.PROVIDER_GOOGLE}
			            region = {{
			                latitude       : this.props.doGetLoggedInformation.location.latitude,
			                longitude      : this.props.doGetLoggedInformation.location.longitude,
			                latitudeDelta  : 0.0922*(0.1),
			                longitudeDelta : 0.0421*(0.1),
		                }}>
		                <Marker
					      	coordinate={{latitude:this.props.doGetLoggedInformation.location.latitude,
				      			longitude:this.props.doGetLoggedInformation.location.longitude}}
				      		tracksViewChanges={this.state.tracksViewChangesOwnerIcon}
					      	title={this.props.doGetLoggedInformation.restaurantName}
					      	description={'Location of your restaurant'}>
					      	<Image
					      		onLoad={this.onLoadOwnerIcon}
					      		source={ownerIcon}
					      		style={{height:30,width:30}}/>
				    	</Marker>
		            </MapView>
		}
	}


	displayAllReceivedRequests = ()=>{
		if(this.state.showRequestsReceived == true){
			return 	<View style = {{
    						height : 290,
    						width: '80%',
    						position: 'absolute',
    						top: '25%',
    						left: '10%',
    						borderColor: '#ddd',
						    borderBottomWidth: 0,
						    shadowColor: '#000',
						    shadowOffset: {
								width: 0,
								height: 5,
							},
							shadowOpacity: 0.34,
							shadowRadius: 6.27,
							elevation: 10,
						    backgroundColor: '#fff',
						    borderRadius: 15
    				}}>
    					<TouchableWithoutFeedback
    						onPress = {()=>this.setState({showRequestsReceived:false})}>
	    					<Text style ={{
			    					height: '10.5%',
			    					width: '12%',
			    					position:'absolute',
			    					top: '2%',
			    					left: '2%',
			    					textAlign:'center',
			    					textAlignVertical: 'center'
			    			}}>
			    				<Icon
			    					style ={{fontSize:19}}
			    					name = 'closesquare'
			    					type = 'AntDesign'/>
			    			</Text>
			    		</TouchableWithoutFeedback>

			    		<View style ={{
			    				height: '80%',
			    				top: '9%',
			    				position: 'relative',
			    				width: '100%'
			    		}}>
			    			{
			    				this.state.requestsReceived.length == 0 ?
			    				<Text style ={{
			    						height: '15%',
			    						textAlign: 'center',
			    						textAlignVertical: 'center',
			    						fontSize: 12.5,
			    						fontWeight: 'bold',
			    						color: '#000',
			    						top: '40%'
			    				}}>
			    					No booking requests recieved yet
			    				</Text>:
				    			<FlatList
				    				data = {this.state.requestsReceived}
									renderItem = {({item}) =>
										<View style = {{
												marginBottom: 10,
												marginTop: 10,
												height: 100,
												width: '100%',
												position: 'relative',
												borderBottomWidth: 1
										}}>
											<Text style ={{
													height: '20%',
													width: '100%',
													position : 'relative',
													fontSize: 13,
													color : '#000',
													paddingLeft: '5%',
													textAlignVertical: 'center'
											}}>
												{'Request made by '+item.fullName}
											</Text>
											<Text style ={{
													height: '20%',
													width: '100%',
													position : 'relative',
													fontSize: 13,
													color : '#000',
													paddingLeft: '5%',
													textAlignVertical: 'center'
											}}>
												{'Gender: '+item.gender}
											</Text>
											<Text style ={{
													height: '20%',
													width: '100%',
													position : 'relative',
													fontSize: 13,
													color : '#000',
													paddingLeft: '5%',
													textAlignVertical: 'center'
											}}>
												{'E-mail: '+item.email}
											</Text>
											<View style ={{
													height: '20%',
													flexDirection: 'row',
													width: '100%',
													position: 'relative',
													top: '6%',
													justifyContent: 'space-between'
											}}>
												<Text style ={{
														height: '100%',
														width: '50%',
														position : 'relative',
														fontSize: 11,
														color : '#000',
														paddingLeft: '5%',
														textAlignVertical: 'center'
												}}>
													{item.time}
												</Text>
												<TouchableWithoutFeedback
													onPress = {()=>this.acceptRequest(item)}>
													<Text style ={{
															height: '100%',
															width: '20%',
															position : 'relative',
															fontSize: 11,
															color : '#000',
															paddingLeft: '5%',
															textAlignVertical: 'center',
															fontWeight: 'bold'
													}}>
														Accept
													</Text>
												</TouchableWithoutFeedback>
												<TouchableWithoutFeedback
													onPress = {()=>this.rejectRequest(item)}>
													<Text style ={{
															height: '100%',
															width: '20%',
															position : 'relative',
															fontSize: 11,
															color : '#000',
															paddingLeft: '5%',
															textAlignVertical: 'center',
															fontWeight: 'bold'
													}}>
														Reject
													</Text>
												</TouchableWithoutFeedback>
											</View>
										</View>
				    				}
									keyExtractor={item=>item.requestkey}/>
							}
			    		</View>	
    				</View>
		}
	}

	render() {
	    return (
	    	<React.Fragment>
	    		<View style = {{
	    				height: '100%',
	    				width: '100%',
	    				position: 'relative'
	    		}}>
    				<View style ={{
    						height: '100%',
    						width: '100%',
    						position: 'relative',
    						alignItems: 'center'
    				}}>
    					{this.displayRestaurantLocation()}
    				</View>
    				{
    					this.props.doGetLoggedInformation.placeStatus == Constants.RESTAURANT_PLACE_STATUS.BLOCKED ?
    					<React.Fragment>
    					</React.Fragment>:
	    				<TouchableWithoutFeedback
	    					onPress = {()=>this.setState({showRequestsReceived:true})}>
			    			<View style = {{
			    					height: 65,
			    					width: 65,
			    					position: 'absolute',
			    					top: 25,
			    					left: '73%',
			    					borderRadius: 90,
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
			    				<Text style ={{
			    						height: '100%',
			    						width: '100%',
			    						position: 'relative',
			    						fontSize: 12,
			    						color: '#000',
			    						textAlignVertical: 'center',
			    						textAlign: 'center',
			    						color: (this.state.alarmRequestNotification == true) ? 
			    							'#f70014':'#000'
			    				}}>
			    					<Icon
			    						style = {{
			    							fontSize : 20,
			    							color    : (this.state.alarmRequestNotification == true) ? 
			    							'#f70014':'#000'}}
			    							
			    						name = 'bell'
			    						type = 'Entypo'/>{'\n'}
			    					Requests
			    				</Text>
			    			</View>
		    			</TouchableWithoutFeedback>
		    		}

		    		{
						this.props.doGetLoggedInformation.placeStatus == Constants.RESTAURANT_PLACE_STATUS.BLOCKED ?
						<React.Fragment>
						</React.Fragment>:
		    			<View style= {{
		    					height: 50,
		    					width: 200,
		    					position: 'absolute',
		    					borderRadius: 100,
		    					flexDirection: 'row',
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
								top: 30,
								left: '15%',
							 	backgroundColor: '#fff'
	    				}}>
	    					{
	    						this.state.loading  == true ?
	    						<Text style = {{
		    							height: '100%',
		    							width: '80%',
		    							textAlignVertical: 'center',
		    							position: 'relative',
		    							left: 20,
		    							color: '#000',
		    							fontSize: 14
		    					}}>
		    						Loading, please wait..
		    					</Text> : 
		    					<React.Fragment>
									<Switch
			    						value = {this.state.inputAcceptBookingFlag}
			    						onChange = {this.handleChangeInAcceptBooking}
			    						style = {{
			    							position: 'relative',
			    							left: 20,
			    							width: '17%',
			    							backgroundColor: '#fff'
			    						}}/>
			    					<Text style = {{
			    							height: '100%',
			    							width: '60%',
			    							textAlignVertical: 'center',
			    							position: 'relative',
			    							left: 20,
			    							color: '#000',
			    							fontSize: 13
			    					}}>
			    						Accepting bookings
			    					</Text>
		    					</React.Fragment>}
	    				</View>
	    			}
    				{this.displayAllReceivedRequests()}
	    		</View> 
	    	</React.Fragment>
		);
  	}
}
