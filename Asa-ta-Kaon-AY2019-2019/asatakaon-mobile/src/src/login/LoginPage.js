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
	TextInput} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
import * as firebase from 'firebase';
import SyncStorage   from 'sync-storage';

/* -- Custom Components  -- */

import Constants from '../commons/Constants.js';


export default class LoginPage extends Component{

	state = {
		loginUsername : '',
		loginPassword : ''
	}

	submitLogin = ()=>{
		if(this.state.loginUsername.length == 0 ||
			this.state.loginPassword.length == 0){

			this.props.doSendAReportMessage('Please fill in username or password fields');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			this.props.doSendAReportMessage('Submitting, Please wait...');
			const loginData = {
				username : this.state.loginUsername,
				password : this.state.loginPassword
			}
			this.props.doSubmitLoginInput(loginData);
		}
		
	}

	render() {
	    return (
	    	<React.Fragment>
	    		<View style = {{
	    				height: '100%',
	    				width: '100%',
	    				position: 'relative',
	    				position: 'absolute',
	    				backgroundColor: '#fff'
	    		}}>
	    		</View>
	    		
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

		    	<View style={{
		    			height: '100%',
		    			width: '100%',
		    			position: 'relative',
		    			alignItems: 'center'
		    	}}>
		    		<Text style={{
		    				height: '10%',
		    				width: '100%',
		    				position: 'relative',
		    				textAlignVertical:'center',
		    				textAlign: 'center',
		    				fontSize: 25,
		    				fontWeight: 'bold',
		    				top: '13%',
		    				color: '#000'
		    		}}>
		    			<Icon
		    				style={{fontSize:35,color: '#000'}}
		    				name = 'login-variant'
		    				type = 'MaterialCommunityIcons'/>
		    			<Icon
		    				style={{fontSize:35,color: '#000'}}
		    				name = 'user'
		    				type = 'FontAwesome'/>{' LOGIN'}

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
		    				height: '60%',
		    				width:'90%',
		    				position: 'relative',
		    				elevation: 11,
							top: '17%',
						    alignItems: 'center',
						    paddingTop: '5%',
						    borderRadius: 25
		    		}}>
		    			<Text style={{
		    					height: '15%',
		    					width: '75%',
		    					position: 'relative',
		    					textAlignVertical: 'center',
		    					fontSize: 15,
		    					fontWeight: 'bold',
		    					color: '#686868'
		    			}}>
		    				USERNAME
		    			</Text>
		    			<View style={{
		    					height: '15%',
		    					width: '75%',
		    					position: 'relative',
		    					flexDirection:'row',
		    					justfiyContent:'center'
		    			}}>
		    				<Text style={{
		    						height: '100%',
		    						width:'23%',
		    						position:'relative',
		    						textAlignVertical:'center',
		    						textAlign:'center'
		    				}}>
		    					<Icon
		    						style={{fontSize:25}}
		    						name = 'user-circle-o'
		    						type = 'FontAwesome'/>
		    				</Text>
		    				<TextInput
		    					placeholder = 'INPUT USERNAME'
			    				style={{
			    					height: '100%',
			    					width: '77%',
			    					position: 'relative',
			    					borderBottomWidth:2,
			    					textAlignVertical:'center'
			    				}}
			    				onChangeText={(loginUsername)=>this.setState({loginUsername})}/>
		    			</View>
		    			<Text style={{
		    					height: '15%',
		    					width: '75%',
		    					position: 'relative',
		    					textAlignVertical: 'center',
		    					fontSize: 15,
		    					fontWeight: 'bold',
		    					color: '#686868',
		    					top: '10%'
		    			}}>
		    				PASSWORD
		    			</Text>
		    			<View style={{
		    					height: '15%',
		    					width: '75%',
		    					position: 'relative',
		    					flexDirection:'row',
		    					justfiyContent:'center',
		    					top: '10%'
		    			}}>
		    				<Text style={{
		    						height: '100%',
		    						width:'23%',
		    						position:'relative',
		    						textAlignVertical:'center',
		    						textAlign:'center'
		    				}}>
		    					<Icon
		    						style={{fontSize:25}}
		    						name = 'lock'
		    						type = 'Entypo'/>
		    				</Text>
		    				<TextInput
		    					secureTextEntry={true}
		    					placeholder = '********'
			    				style={{
			    					height: '100%',
			    					width: '77%',
			    					position: 'relative',
			    					borderBottomWidth:2,
			    					textAlignVertical:'center'
			    				}}
			    				onChangeText={(loginPassword)=>this.setState({loginPassword})}/>
		    			</View>
		    			<TouchableWithoutFeedback
		    				onPress={()=>this.submitLogin()}>
			    			<Text style={{
			    					height: '20%',
			    					width:'50%',
			    					position:'relative',
			    					textAlignVertical:'center',
			    					textAlign:'center',
			    					borderRadius: 100,
			    					fontWeight:'bold',
			    					borderWidth: 2,
			    					borderColor: '#000',
			    					color: '#000',
			    					top: '18%',
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
