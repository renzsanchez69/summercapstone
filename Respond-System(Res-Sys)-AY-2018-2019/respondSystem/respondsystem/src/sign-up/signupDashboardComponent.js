import React, {Component} from 'react';
import {Platform, StyleSheet, Text, Picker, View, AsyncStorage, CheckBox, Image,TextInput,TouchableWithoutFeedback,Alert} from 'react-native';
import { Container, Icon} from 'native-base';


/* -- Custom Components  -- */
import Constants              from '../commons/Constants.js';
import ErrorConnectionPage    from '../commons/errorConnectionPage.js';
import SignUpPartTwoDashboard from './signupP2DashboardComponent.js';

export default class SignUpDashboard extends Component<Props> {

	state = {
		secondPageisPressed  : 'false',
		inputUsername        : '',
		inputPassword        : '',
		inputBirthday        : '',
		inputMonth					 : '',
		inputDay  					 : '',
		inputYear     			 : '',
		inputConfirmPassword : '',
		inputEmail           : '',
		inputFullName        : '',
		inputHomeAddress     : '',
		inputPhoneNumber     : '',
		signUpFirstPageData  : [],
		inputBirthdayError   : '',
		inputPhoneError      : ''
	}


	
	componentDidMount(){
		let currentFirstPageCredential = this.props.getRegistrationCredentials;
		this.setState({
			inputUsername        : currentFirstPageCredential.registerUsername,
			inputPassword        : currentFirstPageCredential.registerPassword,
			inputBirthday        : currentFirstPageCredential.registerBirthday,
			inputConfirmPassword : currentFirstPageCredential.registerConfirmPassword,
			inputEmail           : currentFirstPageCredential.registerEmailAddress,
			inputFullName        : currentFirstPageCredential.registerFullName,
			inputHomeAddress     : currentFirstPageCredential.registerHomeAddress,
			inputPhoneNumber     : currentFirstPageCredential.registerPhoneNumber
		});
	}



	goToNextPageRegistration = ()=>{
		this.props.doDisplayAlertMessage('');
		if(this.state.inputUsername.length<Constants.SIGNUP_FORMS.USERNAME_MIN_LENGTH){
			this.props.doDisplayAlertMessage('Username input length minimum is '+
				Constants.SIGNUP_FORMS.USERNAME_MIN_LENGTH+' characters');
			setTimeout(()=>this.props.doDisplayAlertMessage(''),Constants.SIGNUP_FORMS.ERROR_TIME_DISPLAY);
		}
		else if(this.state.inputPassword.length<Constants.SIGNUP_FORMS.PASSWORD_MIN_LENGTH){
			this.props.doDisplayAlertMessage('Password input length minimum is '+
				Constants.SIGNUP_FORMS.PASSWORD_MIN_LENGTH+' characters');
			setTimeout(()=>this.props.doDisplayAlertMessage(''),Constants.SIGNUP_FORMS.ERROR_TIME_DISPLAY);
		}
		else if(this.state.inputPassword!=this.state.inputConfirmPassword){
			this.props.doDisplayAlertMessage('The password input does not match');
			setTimeout(()=>this.props.doDisplayAlertMessage(''),Constants.SIGNUP_FORMS.ERROR_TIME_DISPLAY);
		}
		// else if(this.state.inputBirthday.length<Constants.SIGNUP_FORMS.BIRTHDAY_MAX_LENGTH){
		// 	this.props.doDisplayAlertMessage('Error on format for birthday input');
		// 	setTimeout(()=>this.props.doDisplayAlertMessage(''),Constants.SIGNUP_FORMS.ERROR_TIME_DISPLAY);
		// }
		else if(this.validateInputBirthday() == false){
			this.props.doDisplayAlertMessage(this.state.inputBirthdayError);
			setTimeout(()=>this.props.doDisplayAlertMessage(''),Constants.SIGNUP_FORMS.ERROR_TIME_DISPLAY);
		}
		else if(this.validateInputPhoneNumber() == false){
			this.props.doDisplayAlertMessage(this.state.inputPhoneError);
			setTimeout(()=>this.props.doDisplayAlertMessage(''),Constants.SIGNUP_FORMS.ERROR_TIME_DISPLAY);
		}
		else{ 
			const data = {
				inputUsername        : this.state.inputUsername,
				inputPassword        : this.state.inputPassword,
				inputBirthday        : this.state.inputMonth+'/'+this.state.inputDay+'/'+this.state.inputYear,
				inputConfirmPassword : this.state.inputConfirmPassword,
				inputEmail           : this.state.inputEmail,
				inputFullName        : this.state.inputFullName,
				inputHomeAddress     : this.state.inputHomeAddress,
				inputPhoneNumber     : this.state.inputPhoneNumber
			} 
			this.props.doSaveFirstCredential(data);
		}
	}

	validateInputPhoneNumber = ()=>{
		let currentPhoneInput = this.state.inputPhoneNumber;
		if(currentPhoneInput.length<Constants.SIGNUP_FORMS.PHONE_NUMBER_MAX_LENGTH){
			this.setState({inputPhoneError:'Invalid phone number input'});
			return false;
		}
		else if(Number.isInteger(Number(currentPhoneInput)) == false){
			this.setState({inputPhoneError:'Error format on input phone number'});
			return false;
		}
		else{
			this.setState({inputPhoneError:''});
			return true;
		}
	}

	validateInputBirthday = ()=>{
		let today      = 	new Date();
		let currentBirthdayInput = this.state.inputBirthday;
		let getMonth   = 	Number(currentBirthdayInput[0]+currentBirthdayInput[1]);
		let getDay     = 	Number(currentBirthdayInput[3]+currentBirthdayInput[4]);
		let getYear    = 	Number(currentBirthdayInput[6]+currentBirthdayInput[7]+
						currentBirthdayInput[8]+currentBirthdayInput[9]);
		let currentYear =	today.getFullYear(); 
		// if(Number.isInteger(getMonth) == false || Number.isInteger(getDay) == false ||
		// 	Number.isInteger(getYear) == false){
		// 	this.setState({inputBirthdayError:'Invalid input for the birthdate, check format'});
		// 	return false;
		// }
		// else 
		if(this.state.inputYear>(currentYear-Constants.SIGNUP_FORMS.MINIMUM_AGE_SIGN)){
			this.setState({inputBirthdayError:'Minimum age requirement is '+
				Constants.SIGNUP_FORMS.MINIMUM_AGE_SIGN+' years old'});
			return false;
		}
		else if(getMonth>12 || getMonth<=0){ // 12 because there are 12 months only
			this.setState({inputBirthdayError:'Please input a valid month for your birthdate'});
			return false;
		}
		else if(getDay>31 || getDay<=0){ // 31 is for 31 days in a month
			this.setState({inputBirthdayError:'Please input a valid day for your birthdate'});
			return false;
		}
		else{ // success formatting for input birthdate
			this.setState({inputBirthdayError:''});
			return true;
		}
	}

	getBackToWelcomePage = ()=>{
		this.props.doSetTemplateDisplay(Constants.PAGES.WELCOME_PAGE);
		this.props.doRefreshCredential();
	}
	minimumUser = ()=>{
		if (this.state.inputUsername.length < Constants.SIGNUP_FORMS.USERNAME_MIN_LENGTH) {
			return <Text style={{
				color: '#e82c2c'
			}}>
			Minimum of{' '+Constants.SIGNUP_FORMS.USERNAME_MIN_LENGTH+' characters'}
			</Text>
		}else{

		}
	}


	minimumPass = ()=>{
		if(this.state.inputPassword.length < Constants.SIGNUP_FORMS.PASSWORD_MIN_LENGTH){
			return <Text style={{
				color: '#e82c2c'
			}}>
			Minimum of{' '+Constants.SIGNUP_FORMS.PASSWORD_MIN_LENGTH+' characters'}
			</Text>
		}else{
			return <Text style={{
				color: '#f5aa42'
			}}>
			Password length is correct
			</Text>
		}
	}

	monthChange = (ItemValue,ItemIndex)=>{

		this.setState({inputMonth:ItemValue});

	}
	dayChange = (ItemValue,ItemIndex)=>{

		this.setState({inputDay:ItemValue});

	}
	yearChange = (ItemValue,ItemIndex)=>{

		this.setState({inputYear:ItemValue});

	}



	signUpDashboardMainDispaly = ()=>{
		if(this.props.doGetValidOrganizations.length!=0){
			return 	<React.Fragment>
				    	<Image source={require('../img/background.png')}
				    		style={{height: '100%',
				    				width:'100%',
				    				resizeMode:'stretch',
				    				position:'absolute',
				    				}}/>
				    	<View style={{
				    		width:'100%',
				    		height:'100%',
				    		alignItems: 'center'
				    	}}>
				    		<View style={{
				    				height: '7%',
				    				width: '100%',
				    				flexDirection: 'row',
				    				position:'relative',
				    				top: '2%',
				    				justifyContent:'flex-start',
				    				alignItems:'center'
				    		}}>
				    			<TouchableWithoutFeedback
				    				onPress={()=>this.getBackToWelcomePage()}>
					    			<Text style={{
					    					height:'100%',
					    					position: 'relative',
					    					width: '10%',
					    					left: '15%'
					    			}}>
					    				<Icon
					    					style={{
					    						fontSize:40,
					    						color: '#454647'
					    					}}
					    					name='ios-arrow-back'
					    					type='Ionicons'/>
					    			</Text>
					    		</TouchableWithoutFeedback>
					    		<Text style={{
				    					height: '100%',
				    					width: '50%',
				    					fontSize: 18,
				    					paddingTop: '2.5%',
				    					fontWeight: 'bold',
				    					textAlign:'center',
				    					textAlignVertical:'center',
				    					position: 'relative',
				    					left: '125%'
				    			}}>
				    				Registration 1 of 2
				    			</Text>
				    		</View>

				    		<View style={{
				    				height: '21%',
				    				width: '100%',
				    				top:'4%',
				    				alignItems: 'center'
				    		}}>
				    			<Text style={{
				    					height:'50%',
				    					width: '25%',
				    					position: 'relative',
				    					textAlignVertical: 'center',
				    					textAlign: 'center'
				    			}}>
				    				<Icon
				    					style={{
				    						fontSize:60,
				    						color: '#454647'
				    					}}
				    					name='fire'
				    					type='FontAwesome'/>
				    			</Text>
				    			<Text style={{
				    					height:'45%',
				    					width: '50%',
				    					position: 'relative',
				    					fontSize: 40,
				    					fontWeight:'bold',
				    					color:'#454647',
				    					textAlignVertical: 'center',
				    					textAlign: 'center'
				    			}}>
				    				Res-Sys
				    			</Text>
				    		</View>

				    		<View style={{
				    				height:'5%',
				    				width:'100%',
				    				top:'6.5%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>	
				    			<Text style={{
				    					width: '38%',
				    					fontSize: 14,
				    					position: 'relative',
				    					left: '30%',
				    					textAlignVertical: 'center'
				    			}}>
				    				CREATE USERNAME
				    			</Text>

				    			<Text style={{
				    					width: '38%',
				    					fontSize: 14,
				    					position: 'relative',
				    					left: '170%',
				    					textAlignVertical: 'center'
				    			}}>
				    				CREATE PASSWORD
				    			</Text>
				    		</View>

				    		<View style={{
				    				height:'7%',
				    				width:'100%',
				    				top:'8%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>
				    			<TextInput
				    				style={{
				    					borderBottomWidth:2,
				    					height: '100%',
				    					width: '45%',
				    					fontSize:11,
				    					left: '13%',
				    					textAlignVertical: 'center',
				    					position: 'relative'
				    				}}
				    				placeholder ='INPUT CREATED USERNAME'
				    				value       ={this.state.inputUsername}
				    				maxLength   ={Constants.SIGNUP_FORMS.USERNAME_MAX_LENGTH } 
				    				onChangeText={(inputUsername)=>this.setState({inputUsername:inputUsername})} />

				    			<TextInput
				    				style={{
				    					borderBottomWidth:2,
				    					height: '100%',
				    					width: '45%',
				    					fontSize:11,
				    					left: '55%',
				    					textAlignVertical: 'center',
				    					position: 'relative'
				    				}}
				    				placeholder    ='INPUT CREATED PASSWORD'
				    				secureTextEntry={true}
				    				value          ={this.state.inputPassword}
				    				maxLength      ={Constants.SIGNUP_FORMS.PASSWORD_MAX_LENGTH }
				    				onChangeText   ={(inputPassword)=>this.setState({inputPassword:inputPassword})} />	

				    		</View>
				    		<View style={{
				    				height:'4%',
				    				width:'100%',
				    				top:'8.6%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>	
				    			<Text style={{
				    					width: '50%',
				    					fontSize: 11,
				    					position: 'relative',
				    					textAlignVertical: 'center',
				    					paddingLeft: '3%',
				    			}}>
				    			{this.minimumUser()}
				    			</Text>
				    			<Text style={{
				    					width: '50%',
				    					fontSize: 11,
				    					position: 'relative',
				    					textAlignVertical: 'center',
				    					paddingLeft: '3%',
				    			}}>
				    			{this.minimumPass()}
				    			</Text>
				    		</View>
				    		<View style={{
				    				height:'5%',
				    				width:'100%',
				    				top:'10%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>	
				    			<Text style={{
				    					width: '38%',
				    					fontSize: 10,
				    					position: 'relative',
				    					left: '30%',
				    					textAlignVertical: 'center'
				    			}}>
				    				CONFIRM PASSWORD
				    			</Text>

				 					<TextInput
				    				style={{
				    					borderBottomWidth:2,
				    					height: '120%',
				    					width: '45%',
				    					fontSize:11,
				    					left: '10%',
				    					textAlignVertical: 'center',
				    					position: 'relative'
				    				}}
				    				placeholder    ='CONFIRM YOUR PASSWORD'
				    				secureTextEntry={true}
				    				value          ={this.state.inputConfirmPassword}
				    				maxLength      ={Constants.SIGNUP_FORMS.PASSWORD_MAX_LENGTH }
				    				onChangeText   ={(inputConfirmPassword)=>this.setState({inputConfirmPassword:inputConfirmPassword})} />
				    		</View>
				    		<View style={{
				    				height:'5%',
				    				width:'100%',
				    				top:'10%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>
				    		<Text style={{
				    					width: '38%',
				    					fontSize: 14,
				    					position: 'relative',
				    					left: '500%',
				    					textAlignVertical: 'center'
				    			}}>
				    				BIRTHDAY
				    			</Text>
			

				    		</View>


				    		<View style={{
				    				height:'7%',
				    				width:'25%',
				    				top:'8%',
				    				borderWidth:2,
		    						borderRadius: 6,
		    						alignItems: 'center',
				    				right: '35%',
				    				color: '#000'
				    		}}>
				    		<Picker
		               		selectedValue = {this.state.inputMonth}
		                	style={{height:'100%',width:'100%'}}
		                	mode="dropdown"
		                	onValueChange = {this.monthChange}>
		                	<Picker.Item label="month" value="month"/>
		                	<Picker.Item label="January" value="01"/>
			                <Picker.Item label="February" value="02"/>
			                <Picker.Item label="March" value="03"/>
			                <Picker.Item label="April" value="04"/>
			                <Picker.Item label="May" value="05"/>
			                <Picker.Item label="June" value="06"/>
			                <Picker.Item label="July" value="07"/>
			                <Picker.Item label="August" value="08"/>
			                <Picker.Item label="September" value="09"/>
			                <Picker.Item label="October" value="10"/>
			                <Picker.Item label="November" value="11"/>
			                <Picker.Item label="December" value="12"/>
		             	</Picker>
		             	</View>
		             	<View style={{
				    				height:'7%',
				    				width:'25%',
				    				top:'1%',
				    				borderWidth:2,
		    						borderRadius: 6,
		    						alignItems: 'center',
				    				left: '-5%',
				    				color: '#000'
				    				}}>
		             	<Picker
		               		selectedValue = {this.state.inputDay}
		                	style={{height:'100%',width:'100%'}}
		                	mode="dropdown"
		                	onValueChange = {this.dayChange}>
		                	<Picker.Item label="day" value="day"/>
		                	<Picker.Item label="1" value="01"/>
			                <Picker.Item label="2" value="02"/>
			                <Picker.Item label="3" value="03"/>
			                <Picker.Item label="4" value="04"/>
			                <Picker.Item label="5" value="05"/>
			                <Picker.Item label="6" value="06"/>
			                <Picker.Item label="7" value="07"/>
			                <Picker.Item label="8" value="08"/>
			                <Picker.Item label="9" value="09"/>
			                <Picker.Item label="10" value="10"/>
			                <Picker.Item label="11" value="11"/>
			                <Picker.Item label="12" value="12"/>
			                <Picker.Item label="13" value="13"/>
			                <Picker.Item label="14" value="14"/>
			                <Picker.Item label="15" value="15"/>
			                <Picker.Item label="16" value="16"/>
			                <Picker.Item label="17" value="17"/>
			                <Picker.Item label="18" value="18"/>
			                <Picker.Item label="19" value="19"/>
			                <Picker.Item label="20" value="20"/>
			                <Picker.Item label="21" value="21"/>
			                <Picker.Item label="22" value="22"/>
			                <Picker.Item label="23" value="23"/>
			                <Picker.Item label="24" value="24"/>
			                <Picker.Item label="25" value="25"/>
			                <Picker.Item label="26" value="26"/>
			                <Picker.Item label="27" value="27"/>
			                <Picker.Item label="28" value="28"/>
			                <Picker.Item label="29" value="29"/>
			                <Picker.Item label="30" value="30"/>
			                <Picker.Item label="31" value="31"/>
		             	</Picker>
		             	</View>
		             	<View style={{
				    				height:'7%',
				    				width:'25%',
				    				top:'-6%',
				    				borderWidth:2,
		    						borderRadius: 6,
		    						alignItems: 'center',
				    				left: '25%',
				    				color: '#000'
				    		}}>
		             	<Picker
		               		selectedValue = {this.state.inputYear}
		                	style={{height:'100%',width:'100%'}}
		                	mode="dropdown"
		                	onValueChange = {this.yearChange}>
		                	<Picker.Item label="Year" value="Year"/>
		                	<Picker.Item label="2024" value="2024"/>
		                	<Picker.Item label="2023" value="2023"/>
		                	<Picker.Item label="2022" value="2022"/>
		                	<Picker.Item label="2021" value="2021"/>
		                	<Picker.Item label="2020" value="2020"/>
		                	<Picker.Item label="2019" value="2019"/>
			                <Picker.Item label="2018" value="2018"/>
			                <Picker.Item label="2017" value="2017"/>
			                <Picker.Item label="2016" value="2016"/>
		                	<Picker.Item label="2015" value="2015"/>
			                <Picker.Item label="2014" value="2014"/>
			                <Picker.Item label="2013" value="2013"/>
			                <Picker.Item label="2012" value="2012"/>
		                	<Picker.Item label="2011" value="2011"/>
			                <Picker.Item label="2010" value="2010"/>
			                <Picker.Item label="2009" value="2009"/>
			                <Picker.Item label="2008" value="2008"/>
		                	<Picker.Item label="2007" value="2007"/>
			                <Picker.Item label="2006" value="2006"/>
			                <Picker.Item label="2005" value="2005"/>
			                <Picker.Item label="2004" value="2004"/>
			                <Picker.Item label="2003" value="2003"/>
			                <Picker.Item label="2002" value="2002"/>
		                	<Picker.Item label="2001" value="2001"/>
			                <Picker.Item label="2000" value="2000"/>
			                <Picker.Item label="1999" value="1999"/>
			                <Picker.Item label="1998" value="1998"/>
			                <Picker.Item label="1997" value="1997"/>
			                <Picker.Item label="1996" value="1996"/>
			                <Picker.Item label="1995" value="1995"/>
			                <Picker.Item label="1994" value="1994"/>
			                <Picker.Item label="1993" value="1993"/>
			                <Picker.Item label="1992" value="1992"/>
			                <Picker.Item label="1991" value="1991"/>
			                <Picker.Item label="1990" value="1990"/>
		             	</Picker>
				    		</View>
				    		<View style={{
				    				height:'7%',
				    				width:'100%',
				    				top:'-10%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>	
				    			<Text style={{
				    					width: '38%',
				    					fontSize: 14,
				    					position: 'relative',
				    					textAlignVertical: 'center',
				    					left: '30%'
				    			}}>
				    				FULL NAME
				    			</Text>

				    			<Text style={{
				    					width: '45%',
				    					fontSize: 14,
				    					position: 'relative',
				    					textAlignVertical: 'center',
				    					left: '170%'
				    			}}>
				    				E-MAIL ADDRESS
				    			</Text>
				    		</View>

				    		<View style={{
				    				height:'7%',
				    				width:'100%',
				    				top:'-15%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>
				    			<TextInput
				    				style={{
				    					borderBottomWidth:2,
				    					height: '100%',
				    					width: '45%',
				    					fontSize:11,
				    					left: '13%',
				    					textAlignVertical: 'center',
				    					position: 'relative'
				    				}}
				    				placeholder ='FIRST & LAST NAME ONLY'
				    				value       ={this.state.inputFullName}
				    				maxLength   ={Constants.SIGNUP_FORMS.FULLNAME_MAX_LENGTH }
				    				onChangeText={(inputFullName)=>this.setState({inputFullName:inputFullName})} />

				    			<TextInput
				    				style={{
				    					borderBottomWidth:2,
				    					height: '100%',
				    					width: '45%',
				    					fontSize:11,
				    					left: '55%',
				    					textAlignVertical: 'center',
				    					position: 'relative'
				    				}}
				    				placeholder ='EMAIL'
				    				value       ={this.state.inputEmail}
				    				maxLength   ={Constants.SIGNUP_FORMS.EMAIL_MAX_LENGTH }
				    				onChangeText={(inputEmail)=>this.setState({inputEmail:inputEmail})} />	

				    		</View>

				    		<View style={{
				    				height:'5%',
				    				width:'100%',
				    				top:'-10%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>	
				    			<Text style={{
				    					width: '38%',
				    					fontSize: 14,
				    					position: 'relative',
				    					textAlignVertical: 'center',
				    					left: '30%'
				    			}}>
				    				HOME ADDRESS
				    			</Text>

				    			<Text style={{
				    					width: '45%',
				    					fontSize: 14,
				    					position: 'relative',
				    					textAlignVertical: 'center',
				    					left: '170%'
				    			}}>
				    				PHONE NUMBER
				    			</Text>
				    		</View>

				    		<View style={{
				    				height:'7%',
				    				width:'100%',
				    				top:'-15%',
				    				flexDirection: 'row',
				    				alignItems:'center'
				    		}}>
				    			<TextInput
				    				style={{
				    					borderBottomWidth:2,
				    					height: '100%',
				    					width: '45%',
				    					fontSize:11,
				    					left: '13%',
				    					textAlignVertical: 'center',
				    					position: 'relative'
				    				}}
				    				placeholder='YOUR HOME ADDRESS'
				    				value      ={this.state.inputHomeAddress}
				    				maxLength  ={Constants.SIGNUP_FORMS.HOME_ADDRESS_MAX_LENGTH }
				    				onChangeText={(inputHomeAddress)=>this.setState({inputHomeAddress:inputHomeAddress})} />

				    			<TextInput
				    				style={{
				    					borderBottomWidth:2,
				    					height: '100%',
				    					width: '45%',
				    					fontSize:11,
				    					left: '55%',
				    					textAlignVertical: 'center',
				    					position: 'relative'
				    				}}
				    				placeholder='YOUR PHONE NUMBER'
				    				value      ={this.state.inputPhoneNumber}
				    				maxLength={Constants.SIGNUP_FORMS.PHONE_NUMBER_MAX_LENGTH }
				    				onChangeText={(inputPhoneNumber)=>this.setState({inputPhoneNumber:inputPhoneNumber})} />

				    		</View>

				    		<TouchableWithoutFeedback
				    			onPress={()=>this.goToNextPageRegistration()}>
					    		<Text style={{
					    				height: '7.5%',
					    				width: '30%',
					    				position: 'relative',
					    				top: '-9%',
					    				color:'#454647',
					    				fontWeight: 'bold',
					    				borderWidth:2,
					    				textAlign: 'center',
		    							textAlignVertical: 'center'
					    		}}>
					    			PROCEED
					    		</Text>
					    	</TouchableWithoutFeedback>
				    	</View>
		    		</React.Fragment>
		}
		else{
			return 	<ErrorConnectionPage
						doSetTemplateDisplay = {this.props.doSetTemplateDisplay} />
		}
	}
	render() {
    	return (
    		<React.Fragment>
    			{this.signUpDashboardMainDispaly()}
    		</React.Fragment>
		);  
  	}
}