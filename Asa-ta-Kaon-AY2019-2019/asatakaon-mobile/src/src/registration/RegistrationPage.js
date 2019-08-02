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


export default class RegistrationPage extends Component{

	state = {
		inputFirstName   : '',
		inputLastName    : '',
		inputAddress     : '',
		inputEmail       : '',
		inputUsername    : '',
		inputPassword    : '',
		inputConfirmPass : '',
		inputGender      : 'Male',
		termsFlag        : false,
		submitted        : false
	}

	onGenderChange = (ItemValue,ItemIndex)=>{
		this.setState({inputGender:ItemValue});
	}

	checkBoxForTerms = ()=>{
		this.setState({termsFlag:!this.state.termsFlag});
	}

	checkUsernameCharacters = ()=>{
		return this.state.inputUsername.match(/^[A-z0-9]+$/);
	}

	submitCredentials = ()=>{
		
		if(this.state.submitted == true){
			return;
		}
		else if(this.checkUsernameCharacters() == false){
			this.props.doSendAReportMessage('Input username should contain letters or digits only');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputPassword!=this.state.inputConfirmPass){
			this.props.doSendAReportMessage('Input passwords does not match');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputFirstName.length == 0){
			this.props.doSendAReportMessage('Please fill in your given first name');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputLastName.length == 0){
			this.props.doSendAReportMessage('Please fill in your given last name');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputAddress.length == 0){
			this.props.doSendAReportMessage('Please fill in your address');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputEmail.length == 0){
			this.props.doSendAReportMessage('Please fill in your e-mail address');
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
		else if(this.state.termsFlag == false){
			this.props.doSendAReportMessage('Please agree to terms of our service');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			const userData = {
				firstName : this.state.inputFirstName,
				lastName  : this.state.inputLastName,
				address   : this.state.inputAddress,
				email     : this.state.inputEmail,
				username  : this.state.inputUsername,
				password  : this.state.inputPassword,
				gender    : this.state.inputGender
			};
			this.setState({submitted:true});
			setTimeout(()=>{
				this.setState({submitted:false});
			},Constants.REPORT_DISPLAY_TIME);
			this.props.doRegisterUser(userData);
		}
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
		    			User Registration
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
		    						width: 120,
		    						position: 'relative',
		    						textAlign: 'center',
		    						fontSize: 16,
		    						fontWeight: 'bold',
		    						color: '#000'
		    				}}>
		    					First Name
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
		    						placeholder = 'input first name'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputFirstName)=>this.setState({inputFirstName})}/>
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
		    					Last Name
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
		    						placeholder = 'input last name'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputLastName)=>this.setState({inputLastName})}/>
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
		    					Address
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
		    						placeholder = 'input your address'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							color: '#000',
		    							textAlign:'center'
		    						}}
		    						onChangeText={(inputAddress)=>this.setState({inputAddress})}/>
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
		    						onChangeText={(inputEmail)=>this.setState({inputEmail})}/>
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
		    					Gender
		    				</Text>

		    				<View style={{
		    						height:40,
		    						width:170,
		    						position: 'relative',
		    						borderRadius: 100,
		    						borderWidth:2,
		    						borderColor: '#000'
		    				}}>
		    					<Picker
						        	style={{height:'100%',width:'100%',position:'relative'}}
						        	onValueChange = {this.onGenderChange}
						        	selectedValue = {this.state.inputGender}>
					        		<Picker.Item 
					        			label={'Male'} 
		        						value={'Male'}/>
		        					<Picker.Item 
					        			label={'Female'} 
		        						value={'Female'}/>
		        					<Picker.Item 
					        			label={'Rather not say'} 
		        						value={'Rather not say'}/>
						 		</Picker>

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
		    						secureTextEntry={true}
		    						placeholder = '********'
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
		    						placeholder = '********'
		    						style={{
		    							height: '100%',
		    							width: '100%',
		    							position: 'relative',
		    							fontSize: 15,
		    							textAlign:'center',
		    							color: '#000'
		    						}}
		    						onChangeText={(inputConfirmPass)=>this.setState({inputConfirmPass})}/>
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
		    					disabled = {this.state.submitted}
		    					onPress={()=>this.submitCredentials()}>
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
			    						borderRadius: 20,
			    						fontWeight: 'bold',
			    						fontSize: 16,
			    						textAlign: 'center',
			    						textAlignVertical: 'center',
			    						top: 10,
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
