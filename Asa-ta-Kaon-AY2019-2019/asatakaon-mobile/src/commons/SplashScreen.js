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
	TouchableWithoutFeedback} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
import * as firebase from 'firebase';
import SyncStorage   from 'sync-storage';

/* -- Custom Components  -- */

import Constants from './Constants.js';


export default class SplashScreen extends Component{

	state = {
		opacityLoadingFlag: false,
		opacityValue      : 1
	}

	animateLoadingLabel = ()=>{
		if(this.state.opacityLoadingFlag == false){
			this.timerHandle =	setTimeout(()=>{
									this.setState({opacityLoadingFlag:true,
										opacityValue:0.5});
									setTimeout(()=>{
										this.setState({opacityLoadingFlag:false,
										opacityValue:1});
									},500);
								},300);
		}
		else return;
	}

	componentWillUnmount(){
		if (this.timerHandle){                   
	        clearTimeout(this.timerHandle);    
	        this.timerHandle = 0;               
	    }
	}

	render() {
		this.animateLoadingLabel();
	    return (
	    	<React.Fragment>
	    		<View style= {{
		    			height: '100%',
		    			width: '100%',
		    			position: 'absolute',
		    			backgroundColor: '#555dff'
		    	}}>
		    	</View>
		    	<Image 
					source={require('../img/background.png')}
		    		style={{
		    			height: '48%',
		    			width: '75%',
		    			position: 'absolute',
		    			resizeMode:'cover',
		    			top: '11%',
		    			left: '12.5%'
	    			}}/>

		    	<View 	style={{
		    				height: '100%',
		    				width: '100%'
		    	}}>
		    		<View style={{
		    				height: '40%',
		    				width: '90%',
		    				left: '5%',
		    				position: 'relative',
		    				top: '45%',
		    				alignItems: 'center'
		    		}}>

			    		<Text style={{
		    					position:'relative',
		    					height: '27%',
		    					width: '60%',
		    					textAlign: 'center',
		    					textAlignVertical: 'center',
		    					top: '30%',
		    					fontSize:14,
		    					fontWeight: 'bold',
		    					color: '#000',
		    					opacity: this.state.opacityValue
		    			}}>
		    				Loading..
		    			</Text>

	    				<Text style={{
		    					position:'relative',
		    					height: '27%',
		    					width: '60%',
		    					textAlign: 'center',
		    					textAlignVertical: 'center',
		    					top: '37%',
		    					fontSize:14,
		    					fontWeight: 'bold',
		    					color: '#000',
		    			}}>
		    				{'Welcome! We will help you\nnavigate through the nearest\nfood establishments in town'}
		    			</Text>


		    		</View>

		    	</View>
		    </React.Fragment>
	    );
  	}
}
