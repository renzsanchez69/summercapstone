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

export default class ReportDispaly extends Component{

	render() {
	    return (
	    	<Text style={{
	    			height: '5.5%',
	    			width: '100%',
	    			position:'absolute',
	    			top: '94.5%',
	    			backgroundColor: '#2f34a3',
	    			fontSize: 14,
	    			color: '#000',
	    			fontWeight: 'bold',
	    			textAlign: 'center',
	    			textAlignVertical: 'center',
	    			elevation: 11
	    	}}>
	    		{this.props.reportMessage}
	    	</Text>
	    );
  	}
}
