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
	ScrollView} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
import RNFetchBlob   from 'react-native-fetch-blob';
import ImagePicker   from 'react-native-image-picker';
import Geolocation   from 'react-native-geolocation-service';

const Blob = RNFetchBlob.polyfill.Blob;
const fs = RNFetchBlob.fs;
window.XMLHttpRequest = RNFetchBlob.polyfill.XMLHttpRequest;
window.Blob = Blob;


/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';


export default class OwnersRestaurant extends Component{
	
	state = {
		imagePath     : '',
		imageFileName : ''
	}


	componentDidMount(){

	}

	editRestaurantAddress = ()=>{
		if(this.props.doGetLoggedInformation.location){
			this.props.doSendAReportMessage('Already edited once, send us a message or a report');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.RESTAURANT_LOCATION);
		}
	}

	removePhoto = ()=>{
		if(!this.props.doGetLoggedInformation.displayIMG){
			this.props.doSendAReportMessage('You haven\'t set any display image yet');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			this.props.doSendAReportMessage('Removing displayed image, please wait..');
			this.props.doUseFirebaseObject
				.storage()
				.ref('restaurantImages')
				.child(String(this.props.doGetLoggedInformation.key))
				.delete()
				.then(()=>{
					this.props.doUseFirebaseObject
						.database()
						.ref("RESTAURANT/"
							+String(this.props.doGetLoggedInformation.key)
							+"/displayIMG")
						.remove()
						.then(()=>{
							this.props.doUseFirebaseObject
								.database()
								.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key))
								.once("value",snapshot=>{
									if(snapshot.exists()){
										const updatedAccountDetails = JSON.parse(JSON.stringify(snapshot.val()));
										this.props.doSetLoggedInformation(updatedAccountDetails);
									}
								})
								.then(()=>{
									this.props.doSendAReportMessage('Successfully removed the displayed image');
							    	setTimeout(()=>{
										this.props.doSendAReportMessage('');
									},Constants.REPORT_DISPLAY_TIME);	
								});
						});
				})	
				.catch((error)=>{
					this.props.doSendAReportMessage('An error has occured, try again');
			    	setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
	}

	selectPhoto = ()=>{
		ImagePicker.showImagePicker(Constants.SELECT_PHOTO_OPTIONS, (response) => {
		  if (response.didCancel) {
		    console.log('User cancelled image picker');
		  } 
		  else if (response.error) {
		    console.log('ImagePicker Error: ', response.error);
		  } 
		  else if (response.customButton) {
		    console.log('User tapped custom button: ', response.customButton);
		  } 
		  else {
		  	console.log(response.fileSize);
		  	console.log(response);
		  	this.props.doSendAReportMessage('Setting as display image, please wait..');
	    	this.setState({
		     	imagePath     : response.uri,
		     	imageFileName : response.fileName
		    });
		    if(response.fileSize>Constants.LOCAL_IMAGE_FILE_SIZE_LIMIT){
		    	this.props.doSendAReportMessage('Error: No greater than'
		    		+String(Number(Constants.LOCAL_IMAGE_FILE_SIZE_LIMIT/1000))
		    		+'MB file');
		    	setTimeout(()=>{
					this.props.doSendAReportMessage('');
				},Constants.REPORT_DISPLAY_TIME);
		    }
		    else{
			    if(response.type){
			    	if(response.type == 'image/jpeg' || response.type == 'jpeg' ||  
			    		response.type == 'image/jpg'){
			    		this.uploadRestaurantImage(String(response.uri),
			    			String(this.props.doGetLoggedInformation.key),
			    			'image/jpg')
				    			.then((response)=>{
				    				this.props.doUseFirebaseObject
				    					.database()
				    					.ref("RESTAURANT/"
				    						+String(this.props.doGetLoggedInformation.key))
				    					.update({
				    						'displayIMG' : String(response)
				    					})
				    					.then(()=>{
				    						this.props.doUseFirebaseObject
				    							.database()
				    							.ref("RESTAURANT/"+String(this.props.doGetLoggedInformation.key))
				    							.once("value",snapshot=>{
				    								if(snapshot.exists()){
				    									const updatedAccountDetails = JSON.parse(JSON.stringify(snapshot.val()));
				    									this.props.doSetLoggedInformation(updatedAccountDetails);
				    								}
				    							})
				    							.then(()=>{
				    								this.props.doSendAReportMessage('Successfully updated restaurant information');
											    	setTimeout(()=>{
														this.props.doSendAReportMessage('');
													},Constants.REPORT_DISPLAY_TIME);
				    							});
				    					});
				    			})
				    			.catch((error)=>{
				    				this.props.doSendAReportMessage('An error has occured, please try again');
							    	setTimeout(()=>{
										this.props.doSendAReportMessage('');
									},Constants.REPORT_DISPLAY_TIME);
				    			});
			    	}
			    	else{
			    		this.props.doSendAReportMessage('Invalid image file, try again');
				    	setTimeout(()=>{
							this.props.doSendAReportMessage('');
						},Constants.REPORT_DISPLAY_TIME);
				    }
			    }
			    else{
			    	this.props.doSendAReportMessage('Invalid image file, try again');
			    	setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
			    }
			}
		  }
		});
	}


	uploadRestaurantImage = (uri,imageName,mime = 'image/jpg')=>{
    	return new Promise((resolve, reject) => {
      		const uploadUri = Platform.OS === 'ios' ? uri.replace('file://', '') : uri;
      		let uploadBlob  = null;
     		const imageRef  = this.props.doUseFirebaseObject.storage().ref('restaurantImages').child(imageName);
      		fs.readFile(uploadUri, 'base64')
      		.then((data) => {
        		return Blob.build(data, { type: `${mime};BASE64` })
      		})
      		.then((blob) => {
        		uploadBlob = blob
        		return imageRef.put(blob, { contentType: mime })
      		})
      		.then(() => {
        		uploadBlob.close()
       			 return imageRef.getDownloadURL()
     		})
      		.then((url) => {
        		resolve(url)
     		})
      		.catch((error) => {
        		reject(error)
     	 	});
   		});
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
	    					Restaurant Information
	    				</Text>
	    			</View>

	    			<View style={{
	    				borderWidth: 1.2,
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
	    				height: '68%',
	    				width:'90%',
	    				position: 'relative',
	    				elevation: 11,
						top: '5.5%',
					    alignItems: 'center',
					    paddingTop: '5%',
					    borderRadius: 25
	    			}}>
	    				<ScrollView 
	    					style = {{width:'100%'}}
		    				contentContainerStyle = {{alignItems:'center',paddingBottom: 30}}>
		    				<View style = {{
		    						height: 115,
		    						width: '90%',
		    						position: 'relative',
		    						flexDirection: 'row'
		    				}}>
		    					<Text style ={{
		    							height: '100%',
		    							position: 'absolute',
		    							width: '43%',
		    							textAlign: 'center',
		    							textAlignVertical:'center',
		    							fontSize: 13,
		    							fontStyle: 'italic'
		    					}}>	
		    						{ this.props.doGetLoggedInformation.displayIMG ? 
		    							'Loading..':'No image'}
		    					</Text>
		    					{
		    						this.props.doGetLoggedInformation.displayIMG ?
		    						<Image
		    							source = {{uri:this.props.doGetLoggedInformation.displayIMG}}
		    							style  = {{
		    								height:'100%',
		    								width:'43%',
		    								position:'relative',
		    								resizeMode: 'contain'
		    							}}/> : <View style = {{height:'100%',width:'43%',position:'relative'}}></View>
		    					}
		    					<View style = {{
		    							position: 'relative',
		    							height: '100%',
		    							width: '56%'
		    					}}>
		    						<Text style = {{
		    								height:'16%',
		    								width: '100%',
		    								position: 'relative',
		    								fontSize: 13,
		    								fontStyle: 'italic',
		    								color: '#000',
		    								paddingLeft: '2%',
		    								top: '7%'
		    						}}>
		    							You may select an image
		    						</Text>
		    						<TouchableWithoutFeedback
		    							onPress = {()=>this.selectPhoto()}>
			    						<Text style ={{
			    								height: '25%',
			    								width: '67%',
			    								position:'relative',
						    					borderColor: '#ddd',
											    borderBottomWidth: 0,
											    shadowColor: '#000',
											    shadowOffset: {
													width: 0,
													height: 2,
												},
												shadowOpacity: 0.34,
												elevation: 6,
											    backgroundColor: '#fff',
											    top: '12%',
											    color: '#000',
											    textAlignVertical: 'center',
											    textAlign: 'center',
											    left: '17%',
											    borderRadius: 15
			    						}}>
			    							Update image
			    						</Text>
			    					</TouchableWithoutFeedback>
			    					<TouchableWithoutFeedback
			    						onPress = {()=>this.removePhoto()}>
			    						<Text style ={{
			    								height: '25%',
			    								width: '67%',
			    								position:'relative',
						    					borderColor: '#ddd',
											    borderBottomWidth: 0,
											    shadowColor: '#000',
											    shadowOffset: {
													width: 0,
													height: 2,
												},
												shadowOpacity: 0.34,
												elevation: 6,
											    backgroundColor: '#fff',
											    top: '25%',
											    color: '#000',
											    textAlignVertical: 'center',
											    textAlign: 'center',
											    left: '17%',
											    borderRadius: 15
			    						}}>
			    							Remove image
			    						</Text>
			    					</TouchableWithoutFeedback>
		    					</View>
		    				</View>
		    				<View style = {{
		    						height: 50,
		    						width: '90%',
		    						position: 'relative',
		    						flexDirection: 'row'
		    				}}>
		    					<Text style = {{
		    							height: '100%',
		    							width: '30%',
		    							position: 'relative',
		    							textAlign: 'center',
		    							textAlignVertical: 'center',
		    							fontSize : 14,
		    							color: '#000'
		    					}}>
		    						Restaurant Name:
		    					</Text>
		    					<Text style = {{
		    							height: '100%',
		    							width: '55%',
		    							position: 'relative',
		    							textAlignVertical: 'center',
		    							fontSize : 13,
		    							color: '#000',
		    							paddingLeft: '2%'
		    					}}>
		    						{this.props.doGetLoggedInformation.restaurantName}
		    					</Text>
		    				</View>
		    				<View style = {{
		    						height: 50,
		    						width: '90%',
		    						position: 'relative',
		    						flexDirection: 'row',
		    				}}>
		    					<Text style = {{
		    							height: '100%',
		    							width: '30%',
		    							position: 'relative',
		    							textAlign: 'center',
		    							textAlignVertical: 'center',
		    							fontSize : 14,
		    							color: '#000'
		    					}}>
		    						Restaurant Status:
		    					</Text>
		    					<Text style = {{
		    							height: '100%',
		    							width: '55%',
		    							position: 'relative',
		    							textAlignVertical: 'center',
		    							fontSize : 13,
		    							color: '#000',
		    							paddingLeft: '2%'
		    					}}>
		    						{( this.props.doGetLoggedInformation.placeStatus == 
		    							Constants.RESTAURANT_PLACE_STATUS.BLOCKED ? 
		    							'Pending for admin confirmation' : 'Successfully validated')}
		    					</Text> 
		    				</View>
		    				<View style = {{
		    						height: 72,
		    						width: '90%',
		    						position: 'relative',
		    						flexDirection: 'row',
		    						marginBottom: 3
		    				}}>
		    					<Text style = {{
		    							height: '100%',
		    							width: '30%',
		    							position: 'relative',
		    							textAlign: 'center',
		    							textAlignVertical: 'center',
		    							fontSize : 14,
		    							color: '#000'
		    					}}>
		    						Current Location: 
		    					</Text>
		    					<Text style = {{
		    							height: '100%',
		    							width: '57%',
		    							position: 'relative',
		    							textAlignVertical: 'center',
		    							fontSize : 13,
		    							color: '#000',
		    							paddingLeft: '2%'
		    					}}>
		    						{(this.props.doGetLoggedInformation.location) ? 
		    							this.props.doGetLoggedInformation.location.addressName : 'Not updated yet\n(Warning: update only work once)' }
		    					</Text>
		    				</View>
		    				<View style = {{
		    						height: 62,
		    						width: '90%',
		    						position: 'relative',
		    						flexDirection: 'row',
		    						marginBottom: 3
		    				}}>
		    					<Text style = {{
		    							height: '100%',
		    							width: '30%',
		    							position: 'relative',
		    							textAlign: 'center',
		    							textAlignVertical: 'center',
		    							fontSize : 14,
		    							color: '#000'
		    					}}>
		    						Business Hours:  
		    					</Text>
		    					<Text style = {{
		    							height: '100%',
		    							width: '57%',
		    							position: 'relative',
		    							textAlignVertical: 'center',
		    							fontSize : 13,
		    							color: '#000',
		    							paddingLeft: '2%'
		    					}}>
		    						{' '+this.props.doGetLoggedInformation.startingHour+' to '+
		    						this.props.doGetLoggedInformation.closingHour}
		    					</Text>
		    				</View>
		    				<View style = {{
		    						height: 62,
		    						width: '90%',
		    						position: 'relative',
		    						flexDirection: 'row',
		    						marginBottom: 3
		    				}}>
		    					<Text style = {{
		    							height: '100%',
		    							width: '30%',
		    							position: 'relative',
		    							textAlign: 'center',
		    							textAlignVertical: 'center',
		    							fontSize : 14,
		    							color: '#000'
		    					}}>
		    						Present Rating:
		    					</Text>
		    					<Text style = {{
		    							height: '100%',
		    							width: '57%',
		    							position: 'relative',
		    							textAlignVertical: 'center',
		    							fontSize : 12.5,
		    							color: '#000',
		    							paddingLeft: '2%',
		    							fontWeight: 'bold'
		    					}}>
		    						{
		    							this.props.doGetLoggedInformation.rate ? 
		    							(Number(this.props.doGetLoggedInformation.rate)/Number(this.props.doGetLoggedInformation.rateCount)
		    								+'/5 rated by '+this.props.doGetLoggedInformation.rateCount + ' users')
		    							:
		    							'Not yet rated by any users'
		    						}
		    					</Text>
		    				</View>
		    				<View style = {{
		    						height: 50,
		    						width: '90%',
		    						position: 'relative',
		    						top:10,
		    						paddingBottom: 5,
		    						alignItems: 'center'
		    				}}>
		    					<TouchableWithoutFeedback
		    						onPress = {()=>this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.EDIT_PRICE_RANGE)}>
			    					<Text style={{
			    							height: '90%',
					    					width:'50%',
					    					position:'relative',
					    					borderColor: '#ddd',
										    borderBottomWidth: 0,
										    shadowColor: '#000',
										    shadowOffset: {
												width: 0,
												height: 2,
											},
											shadowOpacity: 0.34,
											elevation: 6,
										    backgroundColor: '#fff',
										    textAlignVertical: 'center',
										    textAlign: 'center',
										    fontWeight :'bold',
										    borderRadius : 15,
										    color: '#000'
			    					}}>
			    						Your Price Range
			    					</Text>
			    				</TouchableWithoutFeedback>
		    				</View>
		    				<View style = {{
		    						height: 50,
		    						width: '90%',
		    						position: 'relative',
		    						top:15,
		    						paddingBottom: 5,
		    						alignItems: 'center'
		    				}}>
		    					<TouchableWithoutFeedback
		    						onPress={()=>this.editRestaurantAddress()} >
			    					<Text style={{
			    							height: '90%',
					    					width:'50%',
					    					position:'relative',
					    					borderColor: '#ddd',
										    borderBottomWidth: 0,
										    shadowColor: '#000',
										    shadowOffset: {
												width: 0,
												height: 2,
											},
											shadowOpacity: 0.34,
											elevation: 6,
										    backgroundColor: '#fff',
										    textAlignVertical: 'center',
										    textAlign: 'center',
										    fontWeight :'bold',
										    borderRadius : 15,
										    color: '#000'
			    					}}>
			    						Edit Location
			    					</Text>
			    				</TouchableWithoutFeedback>
		    				</View>

		    				<View style = {{
		    						height: 50,
		    						width: '90%',
		    						position: 'relative',
		    						top: 20,
		    						alignItems: 'center'
		    				}}>
		    					<TouchableWithoutFeedback
		    						onPress = {()=>this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.ADD_FOOD_MENU)}>
			    					<Text style={{
			    							height: '90%',
					    					width:'50%',
					    					position:'relative',
					    					borderWidth: 1.2,
										    borderColor: '#ddd',
										    borderBottomWidth: 0,
										    shadowColor: '#000',
										    shadowOffset: {
												width: 0,
												height: 2,
											},
											shadowOpacity: 0.34,
											elevation: 6,
										    backgroundColor: '#fff',
										    textAlignVertical: 'center',
										    textAlign: 'center',
										    fontWeight :'bold',
										    borderRadius : 15,
										    color: '#000'
			    					}}>
			    						Add New Dish
			    					</Text>
			    				</TouchableWithoutFeedback>
		    				</View>
		    				<View style = {{
		    						height: 50,
		    						width: '90%',
		    						position: 'relative',
		    						top: 25,
		    						alignItems: 'center'
		    				}}>
		    					<TouchableWithoutFeedback
		    						onPress = {()=>this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.VIEW_FOOD_MENU)}>
			    					<Text style={{
			    							height: '90%',
					    					width:'50%',
					    					position:'relative',
					    					borderWidth: 1.2,
										    borderColor: '#ddd',
										    borderBottomWidth: 0,
										    shadowColor: '#000',
										    shadowOffset: {
												width: 0,
												height: 2,
											},
											shadowOpacity: 0.34,
											elevation: 6,
										    backgroundColor: '#fff',
										    textAlignVertical: 'center',
										    textAlign: 'center',
										    fontWeight :'bold',
										    borderRadius : 15,
										    color: '#000'
			    					}}>
			    						View Food Menu
			    					</Text>
			    				</TouchableWithoutFeedback>
		    				</View>
		    				<View style = {{
		    						height: 50,
		    						width: '90%',
		    						position: 'relative',
		    						top:30,
		    						paddingBottom: 5,
		    						alignItems: 'center'
		    				}}>
		    					<TouchableWithoutFeedback
		    						onPress = {()=>this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.FEEDBACKS_LIST)}>
			    					<Text style={{
			    							height: '90%',
					    					width:'50%',
					    					position:'relative',
					    					borderColor: '#ddd',
										    borderBottomWidth: 0,
										    shadowColor: '#000',
										    shadowOffset: {
												width: 0,
												height: 2,
											},
											shadowOpacity: 0.34,
											elevation: 6,
										    backgroundColor: '#fff',
										    textAlignVertical: 'center',
										    textAlign: 'center',
										    fontWeight :'bold',
										    borderRadius : 15,
										    color: '#000'
			    					}}>
			    						View Feedbacks
			    					</Text>
			    				</TouchableWithoutFeedback>
		    				</View>
	    				</ScrollView>
	    				<Text 
			    			style={{
			    				height:'9%',
			    				width:'11%',
			    				position:'absolute',
			    				textAlign: 'center',
			    				textAlignVertical:'center',
			    				top: '96%',
			    				left: '3%'
			    		}}>
			    			<Icon
			    				style={{fontSize:30,color:'#000'}}
			    				name = 'ios-arrow-down'
			    				type = 'Ionicons'/>

			    		</Text>
	    			</View>
	    		</View>
	    	</React.Fragment>
		);
  	}
}
