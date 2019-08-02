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
	FlatList,
	ScrollView} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';

export default class Booked extends Component{

	state = {
		loadingBookedData     : true,
		allAcceptedBookings   : [],
		firebaseRequestObject : ''
	}

	componentDidMount(){
		this.listenToBookingsMade();
	}

	componentWillUnmount(){
		this.props.doUseFirebaseObject
			.database()
			.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key)+"/requests")
			.off("value",this.state.firebaseRequestObject);
	}

	listenToBookingsMade = ()=>{
		const firebaseRequestObject =	this.props.doUseFirebaseObject
											.database()
											.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key)+"/requests")
											.on("value",snapshot=>{
												if(snapshot.exists()){
													const currentRequestsReceived  = JSON.parse(JSON.stringify(snapshot.val()));
													const initAllAcceptedRequests  = [];
													Object 
														.keys(currentRequestsReceived)
														.forEach((reqKey)=>{
															if(currentRequestsReceived[reqKey].status 
																== Constants.BOOKING_STATUS.BOOKED ||
																currentRequestsReceived[reqKey].status 
																== Constants.BOOKING_STATUS.CLAIMED ||
																currentRequestsReceived[reqKey].status 
																== Constants.BOOKING_STATUS.NOT_CLAIMED ){
																initAllAcceptedRequests.push(currentRequestsReceived[reqKey])
															}
														});
													this.setState({
														loadingBookedData   : false,
														allAcceptedBookings : initAllAcceptedRequests});
												}
												else {
													this.setState({
														loadingBookedData   : false,
														allAcceptedBookings : []
													});
												}
											});
		this.setState({firebaseRequestObject:firebaseRequestObject})
	}

	reverseData = ()=>{
		// sorts data from latest booked made or requests made down to dated
		const initAllAcceptedRequests = [];
		for(index=(this.state.allAcceptedBookings.length-1);index>=0;index--){
			initAllAcceptedRequests.push(this.state.allAcceptedBookings[index]);
		}
		return initAllAcceptedRequests;
	}

	deleteAcceptedRequest = (request)=>{
		if( request.status == Constants.BOOKING_STATUS.CLAIMED ||
			request.status == Constants.BOOKING_STATUS.NOT_CLAIMED ){
			this.props.doUseFirebaseObject
				.database()
				.ref("RESTAURANT/"
					+String(this.props.doGetLoggedInformation.key)
					+"/requests/"
					+String(request.requestkey))
				.remove()
				.then(()=>{
					this.props.doSendAReportMessage('Deleted');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('Error connecting to the server');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else{
			this.props.doSendAReportMessage('Invalid, you must declared it claimed or unclaimed');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
	}

	setAsClaimed = (accepted)=>{
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

		this.props.doSendAReportMessage('Setting data, please wait..');
		if(accepted.status == Constants.BOOKING_STATUS.BOOKED){
			this.props.doUseFirebaseObject
				.database()
				.ref("USERS/"
					+String(accepted.userKey)
					+"/requests/"
					+String(accepted.requestkey))
				.update({
					'status' : Constants.BOOKING_STATUS.CLAIMED
				})
				.then(()=>{
					this.props.doUseFirebaseObject
						.database()
						.ref("RESTAURANT/"
							+String(this.props.doGetLoggedInformation.key)
							+"/requests/"
							+String(accepted.requestkey))
						.update({
							'status' : Constants.BOOKING_STATUS.CLAIMED
						})
						.then(()=>{
							const notificationKey = 	this.props.doUseFirebaseObject
															.database()
															.ref("USERS/"
																+String(accepted.userKey)
																+"/notifications/")
															.push();
							notificationKey
								.update({
									'message' : String(this.props.doGetLoggedInformation.restaurantName)
										+ ' says you claimed your bookings',
									'date'    : StringDate,
									'status'  : Constants.NOTIFICATION_STATUS.UNREAD,
									'key'     : String(notificationKey.key)
								})
								.then(()=>{
									this.props.doSendAReportMessage('Successfully claimed by user');
									setTimeout(()=>{
										this.props.doSendAReportMessage('');
									},Constants.REPORT_DISPLAY_TIME);
								});
						});
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('Error connecting to the server');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else if(accepted.status == Constants.BOOKING_STATUS.CLAIMED){
			this.props.doSendAReportMessage('Already set as claimed');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}	
		else if(accepted.status == Constants.BOOKING_STATUS.NOT_CLAIMED){
			this.props.doSendAReportMessage('Already set as unclaimed');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}	
	}


	setAsUnclaimed = (accepted)=>{
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

		this.props.doSendAReportMessage('Setting data, please wait..');							
		if(accepted.status == Constants.BOOKING_STATUS.BOOKED){
			this.props.doUseFirebaseObject
				.database()
				.ref("USERS/"
					+String(accepted.userKey)
					+"/requests/"
					+String(accepted.requestkey))
				.update({
					'status' : Constants.BOOKING_STATUS.NOT_CLAIMED
				})
				.then(()=>{
					this.props.doUseFirebaseObject
						.database()
						.ref("RESTAURANT/"
							+String(this.props.doGetLoggedInformation.key)
							+"/requests/"
							+String(accepted.requestkey))
						.update({
							'status' : Constants.BOOKING_STATUS.NOT_CLAIMED
						})
						.then(()=>{
							const notificationKey = 	this.props.doUseFirebaseObject
															.database()
															.ref("USERS/"
																+String(accepted.userKey)
																+"/notifications/")
															.push();
							notificationKey
								.update({
									'message' : String(this.props.doGetLoggedInformation.restaurantName)
										+ " says you did not claim you're booking",
									'date'    : StringDate,
									'status'  : Constants.NOTIFICATION_STATUS.UNREAD,
									'key'     : String(notificationKey.key)
								})
								.then(()=>{
									this.props.doSendAReportMessage('Set as unclaimed by user');
									setTimeout(()=>{
										this.props.doSendAReportMessage('');
									},Constants.REPORT_DISPLAY_TIME);
								});
						});
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('Error connecting to the server');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else if(accepted.status == Constants.BOOKING_STATUS.CLAIMED){
			this.props.doSendAReportMessage('Already set as claimed');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}	
		else if(accepted.status == Constants.BOOKING_STATUS.NOT_CLAIMED){
			this.props.doSendAReportMessage('Already set as unclaimed');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}	
	}

	displayAllAcceptedAndClaimed = ()=>{
		if(this.state.loadingBookedData){
			return 	<Text style ={{
							height: '15%',
							top: '42.5%',
							fontSize: 13,
							color: '#000',
							fontWeight: 'bold',
							textAlign: 'center',
							textAlignVertical: 'center'
					}}>
						Loading booked and claimed requests..
					</Text>
		}
		else if(this.state.allAcceptedBookings.length == 0){
			return 	<Text style ={{
							height: '15%',
							top: '42.5%',
							fontSize: 13,
							color: '#000',
							fontWeight: 'bold',
							textAlign: 'center',
							textAlignVertical: 'center'
					}}>
						No accepted requests yet
					</Text>
		}
		else{
			return 	<FlatList
	    				data = {this.reverseData()}
						renderItem = {({item}) =>
							<View style ={{
									height: 100,
									width: '100%',
									position:'relative',
									marginBottom: 10,
									marginTop: 10,
									borderColor: '#25ba0b',
									borderBottomWidth: 3
							}}>
								<Text style ={{
										height: '18%',
										width: '100%',
										textAlignVertical: 'center',
										paddingLeft: '2%',
										fontSize: 13,
										color: '#000'
								}}>
									{'Name: '+item.fullName}
								</Text>
								<Text style ={{
										height: '18%',
										width: '100%',
										textAlignVertical: 'center',
										paddingLeft: '2%',
										fontSize: 13,
										color: '#000'
								}}>
									{'Gender: '+item.gender}
								</Text>
								<Text style ={{
										height: '18%',
										width: '100%',
										textAlignVertical: 'center',
										paddingLeft: '2%',
										fontSize: 13,
										color: '#000'
								}}>
									{'E-mail: '+item.email}
								</Text>

								<View style ={{
										height: '23%',
										width: '100%',
										justifyContent :'space-between',
										flexDirection: 'row',
										position: 'relative',
										top: '5%'
								}}>
									<Text style ={{
											height: '100%',
											width: '38%',
											position : 'relative',
											fontSize: 10.5,
											color : '#000',
											paddingLeft: '2%',
											textAlignVertical: 'center'
									}}>
										{item.time}
									</Text>
									<TouchableWithoutFeedback
										onPress = {()=>this.setAsUnclaimed(item)}>
										<Text style ={{
												height: '100%',
												width: '19%',
												position : 'relative',
												fontSize: 10,
												color : '#000',
												textAlign: 'center',
												textAlignVertical: 'center',
												fontWeight: 'bold'
										}}>
											{
												item.status  == Constants.BOOKING_STATUS.NOT_CLAIMED ? 
												'Unclaimed' :  'Absent'
											}
										</Text>
									</TouchableWithoutFeedback>
									<TouchableWithoutFeedback
										onPress = {()=>this.setAsClaimed(item)}>
										<Text style ={{
												height: '100%',
												width: '19%',
												position : 'relative',
												fontSize: 10,
												color : '#000',
												paddingLeft: '5%',
												textAlignVertical: 'center',
												fontWeight: 'bold'
										}}>
											{
												item.status  == Constants.BOOKING_STATUS.CLAIMED ? 
												'Claimed' :  'Arrived'
											}
										</Text>
									</TouchableWithoutFeedback>
									<TouchableWithoutFeedback
										onPress = {()=>this.deleteAcceptedRequest(item)}>
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
											Delete
										</Text>
									</TouchableWithoutFeedback>
								</View>
							</View>
						}
						keyExtractor={item=>item.requestkey}/>
		}
	}

	render(){
		return(
			<React.Fragment>
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
								height: 0,
							},
							shadowOpacity: 0.34,
							shadowRadius: 6.27,
							elevation: 9,
						    backgroundColor: '#555dff'
	    			}}>
	    				<Text style={{
	    						height:'100%',
	    						width:'100%',
	    						textAlign:'center',
	    						textAlignVertical:'center',
	    						fontSize:15,
	    						fontWeight:'bold',
	    						color:'#000'
	    				}}>
	    					Booked Users
	    				</Text>
	    			</View>

	    			<View style={{
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
		    				height: '65%',
		    				width:'90%',
		    				position: 'relative',
							top: '5%',
						    alignItems: 'center',
						    paddingTop: '5%',
						    borderRadius: 25
	    			}}>	
	    				<View style ={{
	    						height: '95%',
	    						top: '2.5%',
	    						width: '93%',
	    						position: 'relative'
	    				}}>
	    					{this.displayAllAcceptedAndClaimed()}
	    				</View>
	    			</View>
	    		</View>
			</React.Fragment>
		);
	}
}	