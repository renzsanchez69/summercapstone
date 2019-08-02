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
	CheckBox} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
/* -- Custom Components  -- */
import Constants   from './Constants.js';


export default class ChangePassword extends Component{

	state = {
		currentPassword    : '',
		newPassword        : '',
		confirmNewPassword : ''
	}

	submitChangePassword = ()=>{
		if(this.state.currentPassword!=this.props.doGetLoggedInformation.password){
			this.props.doSendAReportMessage('Current password input is incorrect');
			setTimeout(()=>this.props.doSendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);	
		}
		else if(this.state.newPassword!=this.state.confirmNewPassword){
			this.props.doSendAReportMessage('New password inputs does not match');
			setTimeout(()=>this.props.doSendAReportMessage(''),Constants.REPORT_DISPLAY_TIME);
		}
		else{
			this.props.doChangeUserPassword(this.state.newPassword);
		}
	}

	render() {
	    return (
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
								height: 5,
							},
							shadowOpacity: 0.34,
							shadowRadius: 6.27,
							elevation: 9,
						    backgroundColor: '#555dff',
						    flexDirection: 'row'
	    			}}>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.props.doSetHomePage(Constants.COMMON_ROLE_PAGES.USER_INFO)}>
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
	    						left: '53%'
	    				}}>
	    					Change Password
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
		    				height: '60%',
		    				width:'90%',
		    				position: 'relative',
		    				elevation: 11,
							top: '15%',
						    alignItems: 'center',
						    paddingTop: '5%',
						    borderRadius: 25
		    		}}>
		    			<Text style={{
		    					height:'9.5%',
		    					width:'50%',
		    					textAlign:'center',
		    					textAlignVertical:'center',
		    					fontSize: 16,
		    					fontWeight: 'bold',
		    					color: '#000'
		    			}}>
		    				Current Password
		    			</Text>

		    			<View style={{
		    					height:'15%',
		    					width: '60%',
		    					borderRadius: 100,
		    					position: 'relative',
		    					borderWidth:2,
		    					alignItems:'center'
		    			}}>
		    				<TextInput
		    					secureTextEntry={true}
		    					placeholder = '*********'
		    					style={{
		    						width:'90%',
		    						height:'100%',
		    						textAlign:'center',
		    						textAlignVertical:'center'
		    					}}
		    					onChangeText={(currentPassword)=>this.setState({currentPassword})}/>
		    			</View>

		    			<Text style={{
		    					height:'9.5%',
		    					width:'50%',
		    					textAlign:'center',
		    					textAlignVertical:'center',
		    					fontSize: 16,
		    					fontWeight: 'bold',
		    					color: '#000'
		    			}}>
		    				New Password
		    			</Text>

		    			<View style={{
		    					height:'15%',
		    					width: '60%',
		    					borderRadius: 100,
		    					position: 'relative',
		    					borderWidth:2,
		    					alignItems:'center'
		    			}}>
		    				<TextInput
		    					maxLength = {Constants.CREDENTIALS_POLICY.MAX_PASSWORD}
		    					secureTextEntry={true}
		    					placeholder = '*********'
		    					style={{
		    						width:'90%',
		    						height:'100%',
		    						textAlign:'center',
		    						textAlignVertical:'center'
		    					}}
		    					onChangeText={(newPassword)=>this.setState({newPassword})}/>
		    			</View>

		    			<Text style={{
		    					height:'9.5%',
		    					width:'70%',
		    					textAlign:'center',
		    					textAlignVertical:'center',
		    					fontSize: 16,
		    					fontWeight: 'bold',
		    					color: '#000'
		    			}}>
		    				Confirm New Password
		    			</Text>

		    			<View style={{
		    					height:'15%',
		    					width: '60%',
		    					borderRadius: 100,
		    					position: 'relative',
		    					borderWidth:2,
		    					alignItems:'center'
		    			}}>
		    				<TextInput
		    					maxLength = {Constants.CREDENTIALS_POLICY.MAX_PASSWORD}
		    					secureTextEntry={true}
		    					placeholder = '*********'
		    					style={{
		    						width:'90%',
		    						height:'100%',
		    						textAlign:'center',
		    						textAlignVertical:'center'
		    					}}
		    					onChangeText={(confirmNewPassword)=>this.setState({confirmNewPassword})}/>
		    			</View>
		    			<TouchableWithoutFeedback
		    				onPress={()=>this.submitChangePassword()}>
			    			<Text style={{
			    					height: '17%',
			    					width:'50%',
			    					position:'relative',
			    					textAlignVertical:'center',
			    					textAlign:'center',
			    					borderRadius: 100,
			    					fontWeight:'bold',
			    					borderWidth: 2,
			    					borderColor: '#000',
			    					color: '#000',
			    					borderWidth: 1.2,
								    borderColor: '#ddd',
								    borderBottomWidth: 0,
								    shadowColor: '#000',
								    shadowOffset: {
										width: 0,
										height: 5,
									},
									top:'5%',
									shadowOpacity: 0.34,
									shadowRadius: 6.27,
									elevation: 10,
								    backgroundColor: '#fff',
								    fontSize: 18
			    			}}>
			    				Submit
			    			</Text>
			    		</TouchableWithoutFeedback>
		    		</View>
	    		</View>
	    	</React.Fragment>
    	);
	}
}