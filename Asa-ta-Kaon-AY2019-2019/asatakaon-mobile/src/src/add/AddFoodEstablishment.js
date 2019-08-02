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
import SyncStorage   from 'sync-storage';

/* -- Custom Components  -- */

import Constants from '../commons/Constants.js';


export default class AddFoodEstablishment extends Component{

	state = {
		inputRestaurantName  : '',
		inputStartingHour    : '',
		inputClosingHour     : '',
		inputEmailAddress    : '',
		inputPassword        : '',
		inputUsername        : '',
		inputConfirmPassword : '',
		termsFlag            : false,
		submitted            : false
	}

	checkBoxForTerms = ()=>{
		this.setState({termsFlag:!this.state.termsFlag});
	}

	checkUsernameCharacters = ()=>{
		return this.state.inputUsername.match(/^[A-z0-9]+$/);
	}

	submitFoodEstablishment = ()=>{
		if(this.state.submitted == true)return;
		else if(this.checkUsernameCharacters() == false){
			this.props.doSendAReportMessage('Input username should contain letters or digits only');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputRestaurantName.length == 0){
			this.props.doSendAReportMessage('Please input restaurant name');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.validateBusinessHours()== false){
			this.props.doSendAReportMessage('Invalid input for business hours');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputUsername.length<Constants.CREDENTIALS_POLICY.MIN_USERNAME){
			this.props.doSendAReportMessage('Username input should be minimum of '+
				Constants.CREDENTIALS_POLICY.MIN_USERNAME+' characters');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputPassword.length<Constants.CREDENTIALS_POLICY.MIN_PASSWORD){
			this.props.doSendAReportMessage('Password input should be minimum of '+
				Constants.CREDENTIALS_POLICY.MIN_PASSWORD+' characters');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputPassword!=this.state.inputConfirmPassword){
			this.props.doSendAReportMessage('Input passwords does not match');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputEmailAddress.length==0){
			this.props.doSendAReportMessage('Please fill in your e-mail address');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.termsFlag == false){
			this.props.doSendAReportMessage('Please agree to terms of our service');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			const restaurantData = {
				restaurantName : this.state.inputRestaurantName,
				startingHour   : this.state.inputStartingHour,
				closingHour    : this.state.inputClosingHour,
				email          : this.state.inputEmailAddress,
				username       : this.state.inputUsername,
				password       : this.state.inputPassword
			}
			this.setState({submitted:true});
			this.props.doRegisterRestaurant(restaurantData);
			setTimeout(()=>{
				this.setState({submitted:false});
			},Constants.REPORT_DISPLAY_TIME);
		}	
	}

	validateBusinessHours=()=>{

		if(this.state.inputStartingHour.length<7 || 
			this.state.inputClosingHour.length<7){
			return false;
		}

		const startingHour    = this.state.inputStartingHour[0] +
			this.state.inputStartingHour[1];
		const startingMinutes = this.state.inputStartingHour[3] + 
			this.state.inputStartingHour[4];

		const closingHour     = this.state.inputClosingHour[0] + 
			this.state.inputClosingHour[1];
		const closingMinutes  = this.state.inputClosingHour[3] + 
			this.state.inputClosingHour[4];


		
		if(Number.isInteger(Number(startingHour)) == false ||
			Number.isInteger(Number(startingMinutes)) == false || 
			Number.isInteger(Number(closingHour)) == false || 
			Number.isInteger(Number(closingMinutes)) == false ){
			return false;
		}
		else if(Number(startingHour)>12 || Number(startingHour)<1)return false;
		else if(Number(closingHour)>12 || Number(closingHour)<1)return false;
		else if(Number(startingMinutes)>60 || Number(startingMinutes)<0)return false;
		else if(Number(closingMinutes)>60 || Number(closingMinutes)<0)return false;
		else return true;
	}

	render() {
	    return (
	    	<React.Fragment>

		    	<View style={{
		    				height: '9%',
		    				width: '100%',
		    				position: 'absolute',
		    				backgroundColor: '#555dff'
		    		}}>
		    			<TouchableWithoutFeedback
		    				onPress={()=>this.props.doChangeMainAppDisplay(Constants.APP_PAGES.FIND_RESTAURANT_APP)}>
			    			<Text style={{
			    					height: '50%',
			    					width: '18%',
			    					position: 'relative',
			    					borderWidth: 2,
			    					color: '#000',
			    					fontSize: 13,
			    					fontWeight: 'bold',
			    					textAlign: 'center',
			    					textAlignVertical: 'center',
			    					borderRadius: 100,
			    					top: '20%',
			    					left: '2%'
			    			}}>
			    				RETURN
			    			</Text>
			    		</TouchableWithoutFeedback>
		    	</View>

		    	<View 	style={{
		    				height: '100%',
		    				width: '100%',
		    				position:'relative',
		    				alignItems:'center'
		    	}}>
		    		<Text style={{
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
		    				height: '9%',
		    				width: '70%',
		    				position: 'relative',
		    				textAlignVertical:'center',
		    				textAlign: 'center',
		    				borderRadius:20,
		    				fontSize: 14,
		    				fontWeight: 'bold',
		    				top: '12%',
		    				color: '#000'
		    		}}>	
		    			Restaurant Owner Registration
		    		</Text>

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
		    				height: '70%',
		    				width:'90%',
		    				borderWidth: 2,
		    				borderRadius: 20,
		    				top: '16%',
		    				paddingTop: '5%'
		    		}}>
		    			<ScrollView
		    				style={{width:'100%'}}
		    				contentContainerStyle={{alignItems:'center'}}>

		    				<Text style={{
		    						height:23,
		    						width: 170,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 16,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					Restaurant Name
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<TextInput
		    						placeholder = 'input your restaurant'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputRestaurantName)=>this.setState({inputRestaurantName})}/>
		    				</View>

		    				<Text style={{
		    						height:23,
		    						width: 120,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 16,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					Opening Hour
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<TextInput
		    						placeholder = 'HH:MM(AM/PM)'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputStartingHour)=>this.setState({inputStartingHour})}/>
		    				</View>

		    				<Text style={{
		    						height:23,
		    						width: 120,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 16,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					Closing Hour
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<TextInput
		    						placeholder = 'HH:MM(AM/PM)'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputClosingHour)=>this.setState({inputClosingHour})}/>
		    				</View>


		    				<Text style={{
		    						height:23,
		    						width: 120,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 16,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					E-mail Address
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<TextInput
		    						placeholder = 'input e-mail address'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputEmailAddress)=>this.setState({inputEmailAddress})}/>
		    				</View>
		    				<Text style={{
		    						height:23,
		    						width: 300,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 14,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					Username {'(minimum of '+
		    					Constants.CREDENTIALS_POLICY.MIN_USERNAME + ' characters)'}
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<TextInput
		    						placeholder = 'create username'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputUsername)=>this.setState({inputUsername})}/>
		    				</View>


		    				<Text style={{
		    						height:23,
		    						width: 120,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 16,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					Password
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<TextInput
		    						placeholder = '******'
		    						secureTextEntry={true}
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputPassword)=>this.setState({inputPassword})}/>
		    				</View>

		    				<Text style={{
		    						height:23,
		    						width: 170,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 16,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					Confirm Password
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<TextInput
		    						secureTextEntry={true}
		    						placeholder = '******'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputConfirmPassword)=>this.setState({inputConfirmPassword})}/>
		    				</View>
		    				<View style={{
		    						height:35,
		    						width: 230,
		    						position: 'relative',
		    						flexDirection: 'row',
		    						justifyContent: 'center'
		    				}}>
		    					<CheckBox
				    				value={this.state.termsFlag}
				    				onChange={this.checkBoxForTerms}
				    				style={{
				    					width:'10%',
				    					height:'90%',
				    					position: 'relative'
				    				}}/>
				    			<Text style={{
				    					height:'100%',
				    					width: '80%',
				    					textAlign: 'center',
				    					textAlignVertical:'center',
				    					position: 'relative',
				    					fontSize: 13,
				    					fontWeight: 'bold',
				    					left: '5%',
				    					color: '#000'
				    			}}>
				    				Agree to our terms of service
				    			</Text>
		    				</View>


		    				<TouchableWithoutFeedback
		    					onPress={()=>this.submitFoodEstablishment()}>
			    				<Text style={{
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
			    						height: 55,
			    						width: 140,
			    						position: 'relative',
			    						borderWidth: 2,
			    						borderRadius: 20,
			    						fontWeight: 'bold',
			    						fontSize: 16,
			    						textAlign: 'center',
			    						top:10,
			    						textAlignVertical: 'center',
			    						color: '#000'
			    				}}>
			    						Submit
			    				</Text>
			    			</TouchableWithoutFeedback>

		    				<Text style={{
		    						height: 45,
		    						width: 130,
		    						position: 'relative',
		    						fontWeight: 'bold',
		    						fontSize: 16,
		    						textAlign: 'center',
		    						textAlignVertical: 'center'
		    				}}>
		    				</Text>

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
			    				style={{fontSize:30,color: '#000'}}
			    				name = 'ios-arrow-down'
			    				type = 'Ionicons'/>

			    		</Text>
		    		</View>

		    	</View>
		    </React.Fragment>
	    );
  	}
}
