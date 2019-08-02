import React, 
	{Component} 
	from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage,
	ScrollView,
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
		firebaseAccountObject : '',
		userRating            : '1',
		allReceivedComments   : [],
		inputUserFeedback     : ''
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

		if(this.props.doGetRestaurantDetails.comments){
			const commentsWithKey         = JSON.parse(JSON.stringify(this.props.doGetRestaurantDetails.comments));
			const initAllReceivedComments = [];
			Object
				.keys(commentsWithKey)
				.forEach((commentKey)=>{
					initAllReceivedComments.push(commentsWithKey[commentKey]);
				});
			this.setState({allReceivedComments:initAllReceivedComments});
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

	onRatingChange = (ItemValue,ItemIndex)=>{
		this.setState({userRating:ItemValue});
	}

	submitRating = ()=>{
		this.props.doSendAReportMessage('Submitting your rate, please wait..');
		this.props.doUseFirebaseObject
			.database()
			.ref("RESTAURANT/"+String(this.props.doGetRestaurantDetails.key)
				+"/rateCount")
			.once("value",snapshot=>{
				if(snapshot.exists()){
					var currentRateCount = Number(snapshot.val());
					var currentRate      = '';
					currentRateCount+=1;
					this.props.doUseFirebaseObject
						.database()
						.ref("RESTAURANT/"+String(this.props.doGetRestaurantDetails.key)
							+"/rate")
						.once("value",snapshot=>{
							currentRate = Number(snapshot.val());
							currentRate+= Number(this.state.userRating);
						})
						.then(()=>{
							this.props.doUseFirebaseObject
								.database()
								.ref("RESTAURANT/"+String(this.props.doGetRestaurantDetails.key))
								.update({
									'rate'      : String(currentRate),
									'rateCount' : String(currentRateCount)
								})
								.then(()=>{
									this.props.doSendAReportMessage('Successfully submitted your rate');
									setTimeout(()=>{
										this.props.doSendAReportMessage('');
									},Constants.REPORT_DISPLAY_TIME);
								});
						});
				}
				else{
					this.props.doUseFirebaseObject
						.database()
						.ref("RESTAURANT/"+String(this.props.doGetRestaurantDetails.key))
						.update({
							'rate'      : String(this.state.userRating),
							'rateCount' : '1'
						})
						.then(()=>{
							this.props.doSendAReportMessage('Successfully submitted your rate');
							setTimeout(()=>{
								this.props.doSendAReportMessage('');
							},Constants.REPORT_DISPLAY_TIME);
						});
				}
			})
			.catch((error)=>{
				this.props.doSendAReportMessage('An error has occurred, try again..');
				setTimeout(()=>{
					this.props.doSendAReportMessage('');
				},Constants.REPORT_DISPLAY_TIME);
			});
	}

	submitUserComment = ()=>{
		this.props.doSendAReportMessage('Submitting your feedback, please wait..');
		if(this.state.inputUserFeedback.length < Constants.CREDENTIALS_POLICY.MIN_COMMENT_INPUT){
			this.props.doSendAReportMessage('Minimum of '+Constants.CREDENTIALS_POLICY.MIN_COMMENT_INPUT
				+' characters for comment is required');
				setTimeout(()=>{
					this.props.doSendAReportMessage('');
				},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			const submitCommentKey = 	this.props.doUseFirebaseObject
											.database()
											.ref("RESTAURANT/"+String(this.props.doGetRestaurantDetails.key)
												+"/comments")
											.push();
			submitCommentKey
				.update({
					'key'     : String(submitCommentKey.key),
					'comment' : String(this.state.inputUserFeedback),
					'writer'  : (String(this.props.doGetLoggedInformation.firstName) 
									+ ' ' + String(this.props.doGetLoggedInformation.lastName))
				})
				.then(()=>{
					this.props.doSendAReportMessage('Successfully submitted your feedback');
					this.setState({inputUserFeedback:''});
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('An error has occurred, try again..');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
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
    			<ScrollView
    				style = {{backgroundColor: '#fff',width:'100%',paddingBottom:50}}
    				contentContainerStyle = {{alignItems:'center'}}>
	    			<View style={{
	    					height: 47,
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
	    					height: 150,
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
	    					height: 20,
	    					width: '90%',
	    					textAlignVertical:'center',
	    					fontSize: 12,
	    					top: '2%',
	    					color: '#000'
	    			}}>
	    				{this.props.doGetRestaurantDetails.restaurantName}
	    			</Text>
	    			<Text style ={{
	    					height: 20,
	    					width: '90%',
	    					textAlignVertical:'center',
	    					fontSize: 12,
	    					top: '2%',
	    					color: '#000',
	    					fontWeight: 'bold'
	    			}}>
	    				{'Rating: '+(this.props.doGetRestaurantDetails.rate ?
	    					(Number(this.props.doGetRestaurantDetails.rate)/Number(this.props.doGetRestaurantDetails.rateCount) + '/5'
	    						+' rated by '+this.props.doGetRestaurantDetails.rateCount + ' users')
	    					:'Not rated yet')}
	    			</Text>
	    			<Text style ={{
	    					height: 17,
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
	    					height: 17,
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
	    					height: 35,
	    					width: '90%',
	    					textAlignVertical:'center',
	    					fontSize: 12,
	    					top: '2%',
	    					color: '#000'
	    			}}>
	    				{'Address: '+this.props.doGetRestaurantDetails.location.addressName}
	    			</Text>
	    			<Text style ={{
	    					height: 19,
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
	    					height: 220,
	    					width: '90%',
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
			    	<View 
			    		style = {{
			    			height: 50,
			    			width: '90%',
			    			flexDirection: 'row',
			    			top: '4%'
			    		}}>
			    		<Text 
			    			style ={{
			    				height: '100%',
			    				width: '20%',
			    				position: 'relative',
			    				color: '#000',
			    				fontSize: 15,
			    				textAlignVertical: 'center',
			    			}}>
			    			Rate us
			    		</Text>
			    		<View style ={{
			    				height: '100%',
			    				width: '25.3%',
			    				position: 'relative',
			    				left: '5%',
			    				borderRadius: 100,
			    				borderWidth: 2.3,
			    				color: '#000'
			    			}}>
			    			<Picker
					        	style={{height:'100%',width:'100%',position:'relative'}}
					        	onValueChange = {this.onRatingChange}
					        	selectedValue = {this.state.userRating} >
				        		<Picker.Item 
				        			label={'1'} 
	        						value={'1'}/>
	        					<Picker.Item 
				        			label={'2'} 
	        						value={'2'}/>
	        					<Picker.Item 
				        			label={'3'} 
	        						value={'3'}/>
	        					<Picker.Item 
				        			label={'4'} 
	        						value={'4'}/>
	        					<Picker.Item 
				        			label={'5'} 
	        						value={'5'}/>
					 		</Picker>
			    		</View>
			    		<TouchableWithoutFeedback
			    			onPress = {()=>this.submitRating()}>
				    		<Text style = {{
				    				height: '90%',
				    				width: '40%',
				    				top: '2%',
				    				textAlignVertical: 'center',
									textAlign: 'center',
									color:'#000',
				    				borderRadius: 100,
				    				position: 'relative',
				    				left: '35%',
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
								    backgroundColor: '#fff',
								    fontWeight: 'bold'
				    		}}>
				    			Submit
				    		</Text>
				    	</TouchableWithoutFeedback>
			    	</View>
			    	<Text style ={{
			    			height: 20,
			    			width: '90%',
			    			textAlignVertical: 'center',
			    			fontSize: 14,
			    			fontWeight: 'bold',
			    			color: '#000',
			    			top: '4%'
			    	}}>
			    		Users feedback
			    	</Text>
			    	<View style ={{
			    			height: 55,
			    			width: '80%',
			    			position: 'relative',
			    			top: '5%',
			    			borderWidth: 2,
			    			color: '#000',
			    			borderRadius: 20
			    	}}>
			    		<TextInput 
			    			multiline={true}
			    			value = {this.state.inputUserFeedback}
			    			style ={{
			    				height: '100%',
			    				width: '100%',
			    				position: 'relative',
    							fontSize: 13,
    							paddingLeft: '2%',
    							paddingTop: '2%',
    							textAlignVertical: 'top',
    							color: '#000'
			    			}}
			    			maxLength = {70}
			    			onChangeText={(inputUserFeedback)=>this.setState({inputUserFeedback})}/>
			    	</View>
			    	<TouchableWithoutFeedback
			    		onPress = {()=>this.submitUserComment()} >
				    	<Text style ={{
				    			height: 35,
				    			width: '35%',
				    			position: 'relative',
				    			top: '6%',
				    			color: '#000',
				    			left: '20%',
				    			fontSize: 13,
				    			borderRadius: 100,
				    			textAlignVertical: 'center',
				    			textAlign: 'center',
				    			fontWeight: 'bold',
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
							    backgroundColor: '#fff',
							    fontWeight: 'bold'
				    	}}>
				    		{'Submit comment'}
				    	</Text>
				    </TouchableWithoutFeedback>
			    	<View style ={{
			    			height: 150,
			    			width: '90%',
			    			position: 'relative',
			    			top: '7%'
			    	}}>
			    		{
			    			this.props.doGetRestaurantDetails.comments ?
			    			<React.Fragment>
			    				<FlatList
			    					data = {this.state.allReceivedComments}
			    					renderItem = {({item}) =>
			    						<View style ={{
			    								height: 70,
			    								paddingTop: 5,
			    								width: '95%',
			    								position: 'relative',
			    								left: '2.5%',
			    								borderBottomWidth: 2
			    						}}>
			    							<Text style ={{
			    									height: '100%',
			    									width: '100%',
			    									paddingTop: '2%',
			    									paddingLeft: '2%',
			    									fontSize: 11.5,
			    									color: '#000'
			    							}}>
			    								{item.comment}
			    							</Text>
			    						</View>
			    					}
			    					keyExtractor={item => item.key}/>
			    			</React.Fragment>
			    			:
			    			<Text
			    				style ={{
			    					height: 35,
			    					width: '100%',
			    					position: 'relative',
			    					textAlignVertical: 'center',
			    					textAlign: 'center',
			    					fontSize: 13,
			    					top: 50,
			    					color: '#000',
			    					fontWeight: 'bold'
			    				}}>
			    				No current feedbacks received
			    			</Text>
			    		}
			    	</View>
	    			{
						this.props.doGetRestaurantDetails.acceptBooking == 'false' ? 
		    				<Text style ={{
		    						height: 50,
		    						marginBottom:100,
		    						width: '60%',
		    						position: 'relative',
		    						fontSize: 13,
		    						textAlignVertical: 'center',
		    						textAlign: 'center',
		    						color: '#000',
		    						fontWeight: 'bold',
		    						borderRadius: 100,
		    						borderWidth: 2,
		    						top: '8%'
		    				}}>
		    					Does not accept bookings
		    				</Text>:
		    				<TouchableWithoutFeedback
		    					onPress={()=>this.sendRequest()}>
			    				<Text style ={{
			    						height: 50,
			    						width: '40%',
			    						marginBottom: 100,
			    						position: 'relative',
			    						fontSize: 13,
			    						textAlignVertical: 'center',
			    						textAlign: 'center',
			    						color: '#000',
			    						fontWeight: 'bold',
			    						borderRadius: 100,
			    						top: '8%',
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
    			</ScrollView>
    			<Text style = {{
    					height: '10%',
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