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


export default class RequestsPage extends Component{
	
	state = { 
		requestsMade          : [],
		firebaseRequestObject : ''
	}

	componentDidMount(){
		this.getRequestsMade();
		const firebaseObject = 	this.props.doUseFirebaseObject
									.database()
									.ref("USERS/"+String(this.props.doGetLoggedInformation.accountID))
									.on("value",snapshot=>{
										if(snapshot.exists()){
											const updatedAccountInformation = JSON.parse(JSON.stringify(snapshot.val()));
											this.props.doSetLoggedInformation(updatedAccountInformation);
											this.getRequestsMade();
										}
									});
		this.setState({firebaseRequestObject:firebaseObject});
	}

	componentWillUnmount(){
		this.props.doUseFirebaseObject
			.database()
			.ref("USERS/"+String(this.props.doGetLoggedInformation.accountID))
			.off("value",this.state.firebaseRequestObject);
	}

	getRequestsMade = ()=>{
		if(this.props.doGetLoggedInformation.requests){
			const requestsMadeWithKey = JSON.parse(JSON.stringify(this.props.doGetLoggedInformation.requests));
			const initRequestsMade    = [];
			Object
				.keys(requestsMadeWithKey)
				.forEach((reqKey)=>{
					initRequestsMade.push(requestsMadeWithKey[reqKey]);
				});
			this.setState({requestsMade:initRequestsMade});
		}
		else this.setState({requestsMade:[]});
	}

	deleteRequestSent = (request)=>{
		if(request.status == Constants.BOOKING_STATUS.PENDING){
			this.props.doSendAReportMessage('Cancelling request, please wait..');
			this.props.doUseFirebaseObject
				.database()
				.ref("RESTAURANT/"
					+String(request.restaurantKey)
					+"/requests/"
					+String(request.requestkey))
				.remove()
				.then(()=>{
					this.props.doUseFirebaseObject
						.database()
						.ref("USERS/"
							+String(this.props.doGetLoggedInformation.accountID)
							+"/requests/"
							+String(request.requestkey))
						.remove()
						.then(()=>{
							this.props.doSendAReportMessage('Cancelled');
							setTimeout(()=>{
								this.props.doSendAReportMessage('');
							},Constants.REPORT_DISPLAY_TIME);
						});
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('Error connecting to the server');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
		else if(request.status == Constants.BOOKING_STATUS.BOOKED){
			this.props.doSendAReportMessage('Sorry, refer to our no cancellation policy');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			this.props.doSendAReportMessage('Deleting, please wait...');
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
					this.props.doSendAReportMessage('Error connecting to the server');
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
	    					Requests Sent
	    				</Text>
	    			</View>
	    			<View style ={{
	    					height: '85%',
	    					width: '100%',
	    					position: 'relative'
	    			}}>
	    				{
	    					!this.props.doGetLoggedInformation.requests ?
	    					<Text style = {{
	    							height: '10%',
	    							width: '100%',
	    							top: '36%',
	    							fontSize: 12,
	    							fontWeight: 'bold',
	    							textAlign: 'center',
	    							color: '#000',
	    							textAlignVertical: 'center'
	    					}}>
	    						{'You haven\'t made any requests'} 
	    					</Text>:
	    					(
	    						this.state.requestsMade.length == 0 ?
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
		    					<FlatList
									data = {this.state.requestsMade}
									renderItem = {({item}) =>
										<View style = {{ 
												height: 122,
												position: 'relative',
												borderBottomWidth: 3.3,
												width: '95%',
												left: '2.5%',
												marginBottom: 10,
												marginTop   : 10,
												borderColor : (
													item.status == Constants.BOOKING_STATUS.PENDING ?
													'#e28614':
														(item.status == Constants.BOOKING_STATUS.DENIED ?
														'#e50d0d':'#25ba0b'))
										}}>
											<Text style ={{
													height: '13.4%',
													width: '100%',
													textAlignVertical: 'center',
													fontSize: 13,
													color: '#000',
													paddingLeft: '2%',
													top: '1%'
											}}>
												{item.restaurantName}
											</Text>
											<Text style ={{
													height: '27.3%',
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
													height: '13%',
													width: '100%',
													paddingLeft: '2%',
													textAlignVertical: 'center',
													fontSize: 11.3,
													color: '#000',
													top: '1%',
													fontWeight: 'bold'
											}}>
												{'Operating hours: '
													+item.startingHour
													+'-'
													+item.closingHour}
											</Text>
											<View style ={{
													height: '26%',
													width: '100%',
													position: 'relative',
													top: '4.2%',
													justifyContent: 'space-evenly',
													flexDirection: 'row'
											}}>
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
													{
														item.status == Constants.BOOKING_STATUS.PENDING ? 
														'Pending':
														item.status == Constants.BOOKING_STATUS.DENIED ? 
														'Denied' :
														item.status == Constants.BOOKING_STATUS.BOOKED ?
														'Booked' : 
														item.status == Constants.BOOKING_STATUS.CLAIMED ?
														'Claimed' : 'Unclaimed'
													}
												</Text>
												<TouchableWithoutFeedback
													onPress ={()=>this.deleteRequestSent(item)}>
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
							)
						}
	    			</View>
	    		</View>
	    	</React.Fragment>
		);
  	}
}