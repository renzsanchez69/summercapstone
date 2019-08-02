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
		acceptedRequests       : [],
		requestsFirebaseObject : '',
		loadingData            : true
	}	

	requestListener = ()=>{
		const requestsFirebaseObject = 	this.props.doUseFirebaseObject
											.database()
											.ref("USERS/"+String(this.props.doGetLoggedInformation.accountID)+"/requests")
											.on("value",snapshot=>{
												if(snapshot.exists()){
													const updatedRequestWithKey = JSON.parse(JSON.stringify(snapshot.val()));
													const initAllRequests       = [];
													Object
														.keys(updatedRequestWithKey)
														.forEach((reqKey)=>{
															initAllRequests.push(updatedRequestWithKey[reqKey]);
														});
													this.getAllAcceptedRequests(initAllRequests);
													
												}
												else{
													this.setState({acceptedRequests:[]});
													this.setState({loadingData:false});
												}
											});
		this.setState({requestsFirebaseObject:requestsFirebaseObject});
	}

	componentDidMount(){
		this.requestListener();
	}

	componentWillUnmount(){
		this.props.doUseFirebaseObject
			.database()
			.ref("USERS/"+String(this.props.doGetLoggedInformation.accountID)+"/requests")
			.off("value",this.state.requestsFirebaseObject);
	}

	getAllAcceptedRequests = (requests)=>{
		let initAllAcceptedRequests = 	[];
		initAllAcceptedRequests     = 	requests.map((request)=>{
												if(request.status == Constants.BOOKING_STATUS.BOOKED 
													|| request.status == Constants.BOOKING_STATUS.CLAIMED
													|| request.status == Constants.BOOKING_STATUS.NOT_CLAIMED){
													return request;
												}
											});
		if(initAllAcceptedRequests[0] == undefined){
			this.setState({
				acceptedRequests: [],
				loadingData:false});
		}
		else{
			this.setState({
				acceptedRequests: initAllAcceptedRequests,
				loadingData:false});
		}
		
	}

	deleteRequest = (request)=>{
		if(request.status == Constants.BOOKING_STATUS.BOOKED){
			this.props.doSendAReportMessage('Sorry, refer to our no cancellation policy');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			this.props.doSendAReportMessage('Deleting, please wait..');
			this.props.doUseFirebaseObject
				.database()
				.ref("USERS/"
						+String(this.props.doGetLoggedInformation.accountID)
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
					this.props.doSendAReportMessage('Error in connecting to the server');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
	}

	render() {
	    return (
	    	<React.Fragment>
	    		<View style= {{
		    			height: '100%',
		    			width: '100%',
		    			position: 'absolute',
		    			backgroundColor: '#fff'
		    	}}>
		    	</View>
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
	    					Bookings Made
	    				</Text>
	    			</View>

	    			<View style ={{
	    					height: '85%',
	    					width: '100%',
	    					position: 'relative'
	    			}}>
	    				{
	    					this.state.loadingData == true ? 
	    					<Text style = {{
	    							height: '10%',
	    							width: '100%',
	    							top: '36%',
	    							fontSize: 12,
	    							color: '#000',
	    							fontWeight: 'bold',
	    							textAlign: 'center',
	    							textAlignVertical: 'center'
	    					}}>
	    						Loading requests, please wait..
	    					</Text>:
	    					this.state.acceptedRequests.length == 0 ?
	    					 <Text style = {{
	    							height: '10%',
	    							width: '100%',
	    							top: '36%',
	    							fontSize: 12,
	    							color: '#000',
	    							fontWeight: 'bold',
	    							textAlign: 'center',
	    							textAlignVertical: 'center'
	    					}}>
	    						No accepted or claimed requests yet
	    					</Text>: 
	    					<FlatList
								data = {this.state.acceptedRequests}
								renderItem = {({item}) =>
									<View style = {{ 
											height: 125,
											position: 'relative',
											borderBottomWidth: 3.3,
											width: '95%',
											left: '2.5%',
											marginBottom: 10,
											marginTop   : 10,
											borderColor :'#25ba0b'
									}}>

										<Text style ={{
													height: '12%',
													width: '100%',
													textAlignVertical: 'center',
													fontSize: 11.5,
													color: '#000',
													paddingLeft: '2%',
													top: '1%'
											}}>
												{item.restaurantName}
											</Text>
											<Text style ={{
													height: '25%',
													width: '100%',
													paddingLeft: '2%',
													textAlignVertical: 'center',
													fontSize: 11.5,
													color: '#000',
													top: '1%'
											}}>
												{item.restaurantAddress}
											</Text>
											<Text style ={{
													height: '12%',
													width: '100%',
													paddingLeft: '2%',
													textAlignVertical: 'center',
													fontSize: 12,
													color: '#000',
													top: '1%',
													fontWeight: 'bold'
											}}>
												{'Operating hours: '
													+item.startingHour
													+'-'
													+item.closingHour}
											</Text>
											<Text style ={{
													height: '12%',
													width: '100%',
													paddingLeft: '2%',
													textAlignVertical: 'center',
													fontSize: 11.5,
													color: '#000',
													top: '1%',
													fontWeight: 'bold'
											}}>
												{ 'Status: '+
													( item.status == Constants.BOOKING_STATUS.CLAIMED ?
														'Claimed' :
														item.status == Constants.BOOKING_STATUS.BOOKED ?
														'Booked' : 'Unclaimed')
												}
											</Text>

											<View style ={{
													height: '26%',
													width: '90%',
													position: 'relative',
													top: '2.3%',
													left: '5%',
													justifyContent: 'space-between',
													flexDirection: 'row'
											}}>
												<Text style ={{
														height: '100%',
														width: '50%',
														color: '#000',
														fontSize: 11,
														paddingLeft: '2%',
														textAlignVertical: 'center'
												}}>
													{'Date accepted: '+item.dateAccepted}
												</Text>
												<TouchableWithoutFeedback
													onPress = {()=>this.deleteRequest(item)}>
													<Text style ={{
															height: '100%',
															width:'25%',
															position: 'relative',
															fontSize: 14,
															borderRadius: 100,
															color: '#000',
															textAlignVertical:'center',
															textAlign:'center',
															borderColor: '#ddd',
														    borderBottomWidth: 0,
														    shadowColor: '#000',
														    shadowOffset: {
																width: 0,
																height: 5,
															},
															shadowOpacity: 0.34,
															shadowRadius: 2.27,
															elevation: 10,
														    backgroundColor: '#fff'
													}}>
														Delete
													</Text>
												</TouchableWithoutFeedback>
											</View>

									</View>
								}
								keyExtractor={item=>item.requestkey}/>
	    				}
	    			</View>
	    		</View>
	    	</React.Fragment>
		);
  	}
}